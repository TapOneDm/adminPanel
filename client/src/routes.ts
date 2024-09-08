import Auth from './pages/Auth';
import Dashboard from './pages/Dashboard';
import Artists from './pages/Artists';
import ProductList from './pages/product/ProductList';
import ProductItem from './pages/product/ProductItem';
import Sales from './pages/Sales';
import Settings from './pages/Settings';
import UiKit from './pages/UiKit';
import {
    LOGIN_ROUTE,
    REGISTRATION_ROUTE,
    DASHBOARD_ROUTE,
    ARTISTS_ROUTE,
    PRODUCTS_ROUTE,
    SALES_ROUTE,
    SETTINGS_ROUTE,
    UI_KIT_ROUTE,
} from './utils/utils';

export const routes = [
    {
        path: REGISTRATION_ROUTE,
        Component: Auth,
    },
    {
        path: LOGIN_ROUTE,
        Component: Auth,
    },
    {
        path: DASHBOARD_ROUTE,
        Component: Dashboard,
    },
    {
        path: ARTISTS_ROUTE,
        Component: Artists,
    },
    {
        path: PRODUCTS_ROUTE,
        Component: ProductList,
    },
    {
        path: SALES_ROUTE,
        Component: Sales,
    },
    {
        path: SETTINGS_ROUTE,
        Component: Settings,
    },
    {
        path: UI_KIT_ROUTE,
        Component: UiKit,
    },
    {
        path: PRODUCTS_ROUTE + '/item/:item',
        Component: ProductItem,
    },
];
