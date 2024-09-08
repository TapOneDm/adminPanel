import React, { useEffect, useContext, useState, useCallback } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { Context } from '../../index';
import { observer } from 'mobx-react-lite';
import { Controller, SubmitErrorHandler, SubmitHandler, useForm } from 'react-hook-form';
import AppInput from '../../components/ui/Input/AppInput';
import AppSelect from '../../components/ui/select/AppSelect';
import AppFileUpload from '../../components/ui/file-upload/AppFileUpload';

interface ProductItemForm {
    id?: number; 
    image: any;
    title: string;
    text: string;
    tags: any;
}

const ProductItem = () => {
    const { state } = useLocation();
    const { productStore, commonDataStore } = useContext(Context);
    const { register, getValues, setValue, handleSubmit, reset, watch, control, formState,  } = useForm<ProductItemForm>();
    const navigate = useNavigate();

    const tagList = commonDataStore.tags;

    useEffect(() => {
        (async () => {
            await productStore.getProduct(state.id);
                
            reset({
                id: state.id,
                image: productStore.product.image,
                title: productStore.product.title,
                text: productStore.product.text,
                tags: (productStore.product.tags).map(tag => tag.value),
            }); 
        })();
    }, []);

    const submit: SubmitHandler<ProductItemForm> = async (data) => {
        await productStore.saveProduct(data)
        reset(getValues(), { keepValues: true, keepIsValid: true });
    }

    const revertChanges = async () => {
        navigate(-1)
    }

    const error: SubmitErrorHandler<ProductItemForm> = (data) => {
        console.log(data);
    }

    return (
        <div className='page'>
            {productStore.loading ?
                <div className='page-loading'><i className='icon-spinner'></i></div>
            :
            <div>
                <form onSubmit={handleSubmit(submit, error)}>
                    <div className="notification-line">
                        {formState.isDirty ?
                            <div className="notification-line-actions">
                                    <button
                                        type='submit'
                                        className='form-button form-button-save'
                                    >Save</button>
                                    <button
                                        type='button'
                                        className='form-button form-button-undo'
                                        onClick={revertChanges}
                                    >Cancel</button>
                                </div>
                            : <div className='notification-line-hint'>Edit and save</div>
                        }
                    </div>
                    
                    <Controller
                        name='image'
                        control={control}
                        defaultValue={productStore.product?.image}
                        render={({field}) => <AppFileUpload
                            file={field.value}
                            change={field.onChange}
                        />}
                    />
                    
                    <Controller
                        name='title'
                        control={control}
                        render={({field}) => <AppInput value={field.value} onChange={field.onChange} disabled={field.disabled} />}
                    />

                    <Controller
                        name='text'
                        control={control}
                        render={({field}) => <AppInput value={field.value} onChange={field.onChange} disabled={field.disabled} />}
                    />

                    <Controller
                        name='tags'
                        control={control}
                        defaultValue={productStore.product?.tags}
                        render={({field}) => <AppSelect
                            value={field.value}
                            change={field.onChange}
                            items={tagList}
                            multi={true}
                            disabled={field.disabled}
                        />}
                    />
                </form>
                <br />
            </div>
            }
        </div>
    );
};

export default observer(ProductItem);