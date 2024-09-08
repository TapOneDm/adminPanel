import React, { useState } from 'react';

const ColumnFilter = ({ column }) => {
    const { filterValue, setFilter } = column;

    const preventSort = (e) => {
        e.stopPropagation();
    }

    return (
        <span onClick={(e) => preventSort(e)}>
            <input
                type="text"
                value={filterValue || ''}
                onChange={(e) => setFilter(e.target.value)}
            />
        </span>
    );
};

export default ColumnFilter;
