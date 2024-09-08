import { useContext } from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import { routes } from '../routes';
import { DASHBOARD_ROUTE } from '../utils/utils';
import { Context } from '../index';
import { observer } from 'mobx-react-lite';

const AppRouter = observer(() => {
    const { user } = useContext(Context)

    return (
        <Routes>
            {routes.map(({path, Component}) => 
                <Route key={path} path={path} Component={Component}/>
            )} 
            <Route
                path="*"
                element={<Navigate to={DASHBOARD_ROUTE} replace />}
            />
        </Routes>
    );
});

export default AppRouter;