<?php

namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ActiveRecord extends \yii\db\ActiveRecord
{

    const RECORD_STATUS_ACTIVE = 'ACTIVE';
    const RECORD_STATUS_IN_EDITING = 'IN_EDITING';
    const RECORD_STATUS_DELETED = 'DELETED';
    const RECORD_STATUS_UNLINKED = 'UNLINKED';

    public static function findModel($condition)
    {
        $class = get_class(new static());
        $model = $class::findByCondition($condition)->one();

        if ($model == null) {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'Запись не существует или удалена'));
        }

        return $model;
    }

    public function relinkByIds($ids, $class, $relationName, $delete = true)
    {
        $ids = array_unique(array_filter($ids, fn($id) => !empty($id)));
        $relation = $this->getRelation($relationName);

        if (!is_array($relation->via)) {
            throw new \Exception("Relation $relationName must be connected by via, not viaTable");
        }

        $viaRelation = $relation->via[1];
        $viaClass = $viaRelation->modelClass;
        $viaInverse = lcfirst((new \ReflectionClass($this))->getShortName());

        if (!$ids) {
            $ids = [];
        }

        $idsMap = array_flip($ids);
        $linkRelationField = $relation->link['id'];

        // collect current items
        $idsToDelete = [];
        foreach ($this->$relationName as $item) {
            $idsToDelete[$item['id']] = true;
        }

        $hasSortOrderField = (new $viaClass)->hasAttribute('sort_order');

        // add new items
        foreach ($ids as $id) {
            if (!empty($idsToDelete[$id])) {
                unset($idsToDelete[$id]);
            } else {
                $model = new $viaClass;

                foreach ($viaRelation->link as $a => $b) {
                    $model[$a] = $this->$b;
                }

                foreach ($relation->link as $a => $b) {
                    $model[$b] = $id;
                }

                if ($hasSortOrderField) {
                    $model->sort_order = $idsMap[$model->$linkRelationField];
                }

                $model->populateRelation($viaInverse, $this);
                $model->save(false);
            }
        }

        // remove old items
        if ($delete) {
            foreach ($idsToDelete as $id => $v) {
                $item = $class::findOne(['id' => $id]);

                $columns = [];
                foreach ($viaRelation->link as $a => $b) {
                    $columns[$a] = $this->$b;
                }

                foreach ($relation->link as $a => $b) {
                    $columns[$b] = $item->$a;
                }

                $model = $viaClass::findOne($columns);
                $model->populateRelation($viaInverse, $this);

                $model->delete();
            }
        }

        // update sort_order
        if ($hasSortOrderField) {
            $linkRelationName = $relation->via[0];

            foreach ($this->$linkRelationName as $model) {
                if (isset($idsMap[$model->$linkRelationField]) && $model->sort_order !== $idsMap[$model->$linkRelationField]) {
                    $model->sort_order = $idsMap[$model->$linkRelationField];
                    $model->save(false);
                }
            }
        }
    }

    public function saveNestedModels($class, $attr, $dataAttr, &$data, &$allErrors, $conditions = [], $loadCallback = null, $saveCallback = null, $deleteCallback = null)
    {
        $idsToDelete = [];
        $query = $class::find()->where([$attr => $this->id]);

        if (!empty($conditions)) {
            $query->andWhere($conditions);
        }

        $allItems = $query->all();

        foreach ($allItems as $item) {
            $idsToDelete[$item->id] = true;
        }

        foreach ($data[$dataAttr] as $k => $itemData) {
            if (!empty($itemData['id'])) {
                $model = $class::findOne(['id' => $itemData['id'], $attr => $itemData[$attr]]);
                if ($model == null) {
                    throw new NotFoundHttpException(Yii::t('app', 'Недопустимое значение для поля {attr}', ['attr' => $attr]));
                }
            } else {
                $model = new $class();
                $model->$attr = $this->id;
            }

            $model->load($itemData, '');

            if (array_key_exists('file', $itemData)) {
                $model->loadFile('file_id', $itemData['file']['id'] ?? null);
            }

            $existsTranslations = false;
            if (isset($model->translationModel) && array_key_exists('translations', $itemData)) {
                $model->translationsLoad($itemData);
                $existsTranslations = true;
            }

            if ($loadCallback) {
                call_user_func($loadCallback, $model, $data[$dataAttr][$k]);
            }

            if ($model->hasAttribute('sort_order')) {
                $model->sort_order = $k;
            }

            if (!$model->validate()) {
                $errors = &$data[$dataAttr][$k]['$errors'];
                $errors = $model->getFirstErrors();
                $allErrors = array_unique(array_merge($allErrors, array_values($errors)));
            }

            if (empty($errors)) {
                if (!empty($idsToDelete[$model->id])) {
                    unset($idsToDelete[$model->id]);
                }

                $model->save();

                if ($existsTranslations) {
                    $errors = [];
                    $model->getBehavior('translatable')->translationsSave($data[$dataAttr][$k], $errors);
                    $allErrors = array_unique(array_merge($allErrors, array_values($errors)));
                }

                if ($saveCallback) {
                    $errors = [];
                    call_user_func_array($saveCallback, [$model, $data[$dataAttr][$k], $errors]);
                    $allErrors = array_unique(array_merge($allErrors, array_values($errors)));
                }
            }
        }

        if (!empty($idsToDelete)) {
            $idsToDelete = array_keys($idsToDelete);

            if ($deleteCallback) {
                call_user_func($deleteCallback, $idsToDelete);
            } else {
                // do delete through active record models for correct data changes logging
                foreach ($class::find()->andWhere(['id' => $idsToDelete])->all() as $deleteModel) {
                    $deleteModel->delete();
                }
            }
        }
    }

    public function saveNestedModelsById($class, $attr, $nestedAttr, $dataAttr, &$data, &$allErrors)
    {
        $idsToDeleted = $class::find()->where([$attr => $this->id])->indexBy($nestedAttr)->asArray()->all();;

        foreach ($data[$dataAttr] as $idx => $itemData) {

            $model = $class::findOne([
                $attr => $this->id,
                $nestedAttr => $itemData[$nestedAttr] ?? null
            ]);

            if ($model == null) {
                $model = new $class();
                $model->$attr = $this->id;
            } else {
                unset($idsToDeleted[$model->$nestedAttr]);
            }

            $model->load($itemData, '');

            if ($model->validate()) {
                $model->save();
            } else {
                $errors = &$data[$dataAttr][$idx]['$errors'];
                $errors = $model->getFirstErrors();
                $allErrors = array_unique(array_merge($allErrors, array_values($errors)));
            }
        }

        if (!empty($idsToDeleted)) {
            $deleteModels = $class::find()->andWhere([$attr => $this->id, $nestedAttr => array_keys($idsToDeleted)])->all();
            foreach ($deleteModels as $deleteModel) {
                $deleteModel->delete();
            }
        }
    }


    public static function sortModels($data, $primaryAttr = 'id', $sortAttr = 'sort_order')
    {
        if (!method_exists(new static(), 'tableName') || empty($data)) {
            return;
        }

        $tableName = static::tableName();
        $primaryItems = ArrayHelper::getColumn($data, $primaryAttr);
        $sortItems = ArrayHelper::getColumn($data, $sortAttr);

        if (static::getTableSchema()->getColumn($primaryAttr)->type == 'bigint') {
            $querySelectPrimary = "unnest(array[" . implode(', ', $primaryItems) . "])";
        } else {
            $querySelectPrimary = "unnest(array['" . implode("','", $primaryItems) . "']::uuid[])";
        }

        $querySelectSort = "unnest(array[" . implode(', ', $sortItems) . "])";

        Yii::$app->db->createCommand('
            UPDATE "' . $tableName . '"
            SET "' . $sortAttr . '" = "tmp"."' . $sortAttr . '"
            FROM (
                SELECT
                    ' . $querySelectPrimary . ' as "' . $primaryAttr . '",
                    ' . $querySelectSort . ' as "' . $sortAttr . '"
            ) "tmp"
            WHERE "tmp"."' . $primaryAttr . '" = "' . $tableName . '"."' . $primaryAttr . '"
        ')->execute();
    }
}
