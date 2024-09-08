import { useContext, useEffect } from 'react';
import './App.scss';

import LoginForm from './components/login-form/LoginForm';
import { Context } from './index';
import { observer } from 'mobx-react-lite';
import { BrowserRouter } from 'react-router-dom';

import AppRouter from './components/AppRouter';
import NavBar from './components/navbar/NavBar';
import Header from './components/header/Header';

function App() {
    const { user, commonDataStore } = useContext(Context);

    useEffect(() => {
        if (localStorage.getItem('token')) {
            user.checkAuth();
            (async () => {
                await commonDataStore.getData();
            })();
        }
    }, []);

    if (!user._isAuth) {
        return (
            <div>
                <LoginForm />
            </div>
        );
    }

    return (
        <div className="wrapper">
            <BrowserRouter>
                <NavBar />
                <div className="content">
                    <Header />
                    <div className="content-body">
                        <div className="outlet">
                            <AppRouter />
                        </div>
                    </div>
                </div>
            </BrowserRouter>
        </div>
    );
}

export default observer(App);




