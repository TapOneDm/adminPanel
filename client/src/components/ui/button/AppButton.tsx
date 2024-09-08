import React, { useEffect, useState } from 'react';

import "./AppButton.scss"

const AppButton = (props) => {
    const { children, icon, disabled, loading, onClick } = props 
    
    const isLoading = () => {
        return loading ? 'loading' : '';
    }

    return (
        <button
            className={`button ${isLoading()}`}
            disabled={disabled}
            onClick={onClick}
        >
            <i className={`icon-${icon}`}></i>{children}
            <span className='button-spinner'><i className='icon-spinner'></i></span>
        </button>
    );
};

export default AppButton;