import React, { useState } from 'react';

import AppSelect from '../components/ui/select/AppSelect';

const selectMockOptions = [
    {
        value: 'new',
        label: 'New',
    },
    {
        value: 'processed',
        label: 'Processed',
    },
    {
        value: 'completed',
        label: 'Completed',
    },
    {
        value: 'rejected',
        label: 'Rejected',
    },
];

const isMulti = true;

const UiKit = () => {
    const [activeModal, setActiveModal] = useState<boolean>(false);

    const initModal = () => {
        setActiveModal(true)
    }


    return (
        <div style={{height: '100%'}}>
            <h1 onClick={initModal}>Dashboard</h1>
            <AppSelect
                value={''}
                searchable={false}
                multi={isMulti}
                items={selectMockOptions}
            />
        </div>
    );
};

export default UiKit;
