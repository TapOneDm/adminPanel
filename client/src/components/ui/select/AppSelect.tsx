import React, { useEffect, useState } from 'react';
import Select from 'react-select';

import makeAnimated from 'react-select/animated';
import './AppSelect.scss';

const animatedComponent = makeAnimated();

const AppSelect = (props) => {
    const { value, searchable, multi, items, change } = props;
    const [state, setState] = useState<any>();

    if (!value) {
        return (
            <div>Loading</div>
        );
    }

    // eslint-disable-next-line react-hooks/rules-of-hooks
    useEffect(() => {
    }, [state])

    const getValue = () => {
        if (state) {
            if (multi) {
                return (items as Array<any>).filter(
                    (option) =>
                        (state as Array<any>).indexOf(option.value) >= 0,
                );
            }

            let res = (items as Array<any>).find(
                (option) => option.value === state,
            )
            return res;
        } else {
            if (multi) {
                return items.filter(item => value.includes(item.value));
            }
            
            return items.find(item => item.value === value);
        }
    };

    const onChange = (newValue: any) => {
        let value;

        if (multi) {
            value = (newValue as Array<any>).map((v) => v.value);
            setState(value);
        } else {
            value = newValue.value;
            setState(value);
        }

        change(value)
    };

    return (
        <Select
            className='app-select'
            classNamePrefix='app-select'
            isSearchable={searchable ?? false}
            isMulti={multi ?? false}
            onChange={onChange}
            value={getValue()}
            options={items}
            components={animatedComponent}
        />
    );
};

export default AppSelect;
