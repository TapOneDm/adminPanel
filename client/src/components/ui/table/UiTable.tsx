import React, { useEffect, useMemo, useRef, useState } from 'react';
import { useTable, useSortBy, useGlobalFilter, useFilters } from 'react-table';

import './UiTable.scss';
import Modal from '../modal/Modal';
import ColumnFilter from './addons/ColumnFilter';

const UiTable = (props) => {
    const [modalConfirmActive, setModalConfirmActive] = useState<boolean>(false);

    const columns = useMemo(() => props.columns, []);
    const data = useMemo(() => props.data, []);

    const defaultColumn = useMemo(() => {
        return {
            Filter: ColumnFilter,
        };
    }, []);

    const table = useTable(
        {
            columns: columns,
            data: data,
            defaultColumn,
        },
        useFilters,
        useGlobalFilter,
        useSortBy,
    );

    const showFilter = (e) => {
        e.stopPropagation();
        let headerTitle = (e.target as HTMLElement).parentElement;
        headerTitle.nextElementSibling.classList.add('active');
    }

    const closeFilter = (e, header) => {
        e.stopPropagation();
        let filter = (e.target as HTMLElement).parentElement;
        filter.classList.remove('active');
        header.setFilter('');
    }

    const { deleteCallback } = props

    const deleteRecord = (row) => {
        console.log(row.original.id);
        setModalConfirmActive((prev) => !prev);
        return deleteCallback(row.original.id)
    }


    return (
        <>
            <div {...table.getTableProps()} className="table">
                    {(table.headerGroups as Array<any>).map((headerGroup) => (
                        <div {...headerGroup.getHeaderGroupProps()} className='table-header'>
                            {(headerGroup.headers as Array<any>).map(
                                (header) => (
                                    <div
                                        className='table-header-cell cell'
                                        {...header.getHeaderProps(
                                            header.getSortByToggleProps(),
                                        )}
                                    >
                                        <div className='cell-title'>
                                            {header.canFilter && <i className='icon-search cell-search' onClick={showFilter}></i>}
                                            {header.render('Header')}
                                            {header.isSorted ? (
                                                header.isSortedDesc ? (
                                                    <i className="icon-caret-down"></i>
                                                ) : (
                                                    <i className="icon-caret-up"></i>
                                                )
                                            ) : (
                                                <i className="icon-sort"></i>
                                            )}
                                        </div>
                                        <div className='cell-filter'>
                                            {header.canFilter
                                                ? header.render('Filter')
                                                : null}
                                                <i
                                                    className='icon-times cell-filter-close'
                                                    onClick={(e) => closeFilter(e, header)}
                                                ></i>
                                        </div>
                                    </div>
                                ),
                            )}
                            <div className='table-header-cell cell actions'>
                                Actions
                            </div>
                        </div>
                    ))}
                <div {...table.getTableBodyProps()} className='table-body'>
                    {(table.rows as Array<any>).map((row) => {
                        table.prepareRow(row);
                        return (
                            <div {...row.getRowProps()} className='table-body-row'>
                                {(row.cells as Array<any>).map((cell) => {
                                    return (
                                        <div {...cell.getCellProps()} className='table-body-cell cell'>
                                            {cell.render('Cell')}
                                        </div>
                                    );
                                })}
                                <div className='table-body-cell cell actions'>
                                    <i className='icon-pencil-alt'></i>
                                    <i className='icon-trash-alt' onClick={() => deleteRecord(row)}></i>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
            <Modal
                type={'confirm'}
                data={
                    {
                        title: 'Delete',
                        titleIcon: 'exclamation-circle',
                        text: 'Are you sure you want to delete this record?'
                    }
                }
                active={modalConfirmActive}
                setActive={setModalConfirmActive}
                onConfirm={deleteRecord}
            />
        </>
    );
};

export default UiTable;
