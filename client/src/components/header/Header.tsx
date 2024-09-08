import React, { useContext, useEffect } from 'react';
import { Context } from '../..';

import './Header.scss';

const Header = () => {
    const { user } = useContext(Context);


    return (
        <div className="header">
            <div className="header-search">
                <i className='icon-search'></i>
                <input type="text"  placeholder='Search'/>
            </div>
            <div className="header-user">
                <div className="header-user-info">
                    <div className="header-user-name">{user.email}</div>
                    <div className="header-user-role">Admin</div>
                </div>
                <div className="header-user-notification"><i className='icon-bell'></i></div>
            </div>
        </div>
    );
};

export default Header;
