import React from 'react';
import './AppInput.scss';

const AppInput = (props) => {
    
    const { name, value, onChange, onBlur, disabled } = props;
    
    const blur = () => {
        return onBlur ?? null;
    }

    return (
        <input
            className='app-input'
            name={name}
            value={value ?? ''}
            onChange={onChange}
            onBlur={blur}
            disabled={disabled ?? false}    
        />
    );
};

export default AppInput;