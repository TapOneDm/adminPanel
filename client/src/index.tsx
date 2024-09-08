import React, { createContext } from 'react';
import ReactDOM from 'react-dom/client';
import './assets/index.scss';
import App from './App';
import UserStore from './store/userStore';
import ProductStore from './store/productStore';
import CommonDataStore from './store/commonDataStore';

const root = ReactDOM.createRoot(
    document.getElementById('root') as HTMLElement,
);

const user = new UserStore();
const productStore = new ProductStore();
const commonDataStore = new CommonDataStore();

export const Context = createContext({
    user,
    commonDataStore,
    productStore,
});

root.render(
    <Context.Provider value={{
        user,
        commonDataStore,
        productStore,
    }}>
        <App />
    </Context.Provider>,
);
