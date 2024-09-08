import { useContext } from 'react';
import { Context } from '../../index';

import './NavBar.scss';
import logo from './logo.png';
import { NavLink } from 'react-router-dom';
import {
    DASHBOARD_ROUTE,
    ARTISTS_ROUTE,
    PRODUCTS_ROUTE,
    SALES_ROUTE,
    SETTINGS_ROUTE,
    UI_KIT_ROUTE,
} from '../../utils/utils';

const NavBar = () => {
    const { user } = useContext(Context);

    return (
        <div className="navbar">
            <div className="navbar-content">
                <div className="navbar-content-logo">
                    <NavLink to={DASHBOARD_ROUTE}>
                        <img src={logo} alt="logo" />
                    </NavLink>
                </div>

                <div className="navbar-content-items items-mid">
                    <NavLink to={UI_KIT_ROUTE} className="nav-link">
                        <i className="icon-burger-soda"></i>Ui Kit
                    </NavLink>
                    <NavLink to={DASHBOARD_ROUTE} className="nav-link">
                        <i className="icon-monitor-heart-rate"></i>Dashboard
                    </NavLink>
                    <NavLink to={ARTISTS_ROUTE} className="nav-link">
                        <i className="icon-user-alt"></i>Artists
                    </NavLink>
                    <NavLink to={PRODUCTS_ROUTE} className="nav-link">
                        <i className="icon-images"></i>Products
                    </NavLink>
                    <NavLink to={SALES_ROUTE} className="nav-link">
                        <i className="icon-ticket-alt"></i>Sales
                    </NavLink>
                </div>

                <div className="navbar-content-items items-bottom">
                    <NavLink to={SETTINGS_ROUTE} className="nav-link">
                        <i className="icon-cog"></i>Settings
                    </NavLink>
                    <button onClick={() => user.logout()} className="nav-link logout">
                        <i className="icon-sign-out-alt"></i>Logout
                    </button>
                </div>
            </div>
        </div>
    );
};

export default NavBar;
