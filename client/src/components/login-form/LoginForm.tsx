import React, { FC, useContext, useState } from 'react';

import { Context } from '../../index';
import { observer } from 'mobx-react-lite';

import './LoginForm.scss';

const LoginForm: FC = () => {
    const [email, setEmail] = useState<string>('');
    const [password, setPassword] = useState<string>('');
    const { user, commonDataStore } = useContext(Context);

    async function login() {
        await user.login(email, password);
        if (user.isAuth) {
            await commonDataStore.getData();
        }
    }

    return (
        <div className="login-form-wrapper">
            <div className="login-form">
                <h2>Welcome</h2>
                <div className="form-group">
                    <input
                        onChange={(e) => setEmail(e.target.value)}
                        value={email}
                        type="text"
                        placeholder="Email"
                    />
                </div>
                <div className="form-group">
                    <input
                        onChange={(e) => setPassword(e.target.value)}
                        value={password}
                        type="password"
                        placeholder="Password"
                    />
                </div>
                <div className="form-actions">
                    <button type='button' onClick={login}>
                        Login
                    </button>
                    <button type='button' onClick={() => user.registration(email, password)}>
                        Register
                    </button>
                </div>
            </div>
        </div>
    );
};

export default observer(LoginForm);
