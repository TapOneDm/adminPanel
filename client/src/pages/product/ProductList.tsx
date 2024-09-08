import React, { useEffect, useMemo, useRef, useState, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import Modal from '../../components/ui/modal/Modal';
import ColumnFilter from '../../components/ui/table/addons/ColumnFilter';
import { useTable, useSortBy, useGlobalFilter, useFilters } from 'react-table';
import { Context } from '../../index';
import { observer } from 'mobx-react-lite';
import { PRODUCTS_ROUTE } from '../../utils/utils';

const COLUMNS = [
    {
        Header: 'Title',
        accessor: 'title',
        disableFilters: false,
    },
    {
        id: 'show_on_website',
        Header: 'Website',
        accessor:  d => d.show_on_website.toString(),
        disableFilters: false,
    },
    {
        Header: 'Price',
        accessor: 'price',
        disableFilters: false,
    },
    
    // {
    //     Header: 'Gender',
    //     accessor: 'gender',
    //     disableFilters: true,
    // },
    // {
    //     Header: 'Birthday',
    //     accessor: 'birthday',
    //     Cell: ({ value }) => {
    //         return format(new Date(value), 'dd/MM/yyyy');
    //     },
    //     disableFilters: true,
    // },
];

const ProductList = () => {
    const navigate = useNavigate();
    // const [items, setItems] = useState([]);
    const { productStore } = useContext(Context);
    const [chosenRecordId, setChosenRecordId] = useState(null);
    const [modalConfirmActive, setModalConfirmActive] = useState<boolean>(false);

    useEffect(() => {
        productStore.getProducts()
    }, [])

    const defaultColumn = useMemo(() => {
        return {
            Filter: ColumnFilter,
        };
    }, []);

    const table = useTable(
        {
            columns: COLUMNS,
            data: productStore.products,
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

    const deleteRecord = () => {
        return productStore.deleteProduct(chosenRecordId);
    }

    const onDelete = (e, row) => {
        e.stopPropagation();
        setChosenRecordId(row.original.id);
        setModalConfirmActive(true)
    }

    const navigateToItem = (id) => {
        navigate(PRODUCTS_ROUTE  + '/item/' + id, { state: { id: id } });
    }

    return (
        <div className='page'>
             <>
             <div {...table.getTableProps()} className="table">
                     {(table.headerGroups as Array<any>).map((headerGroup) => {
                        const {key, ...headerGroupProps} = headerGroup.getHeaderGroupProps()
                        return (
                         <div key={key} {...headerGroupProps} className='table-header'>
                             {(headerGroup.headers as Array<any>).map(
                                 (header) => { 
                                    const {key, ...headerProps} = header.getHeaderProps(header.getSortByToggleProps());
                                    return (
                                     <div
                                        key={key}
                                        {...headerProps}
                                        className='table-header-cell cell'
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
                                 )},
                             )}
                             <div className='table-header-cell cell actions'>
                                 Actions
                             </div>
                         </div>
                     )})}
                 <div {...table.getTableBodyProps()} className='table-body'>
                    {productStore.loading && 
                        <div className="table-body-loading"><i className='icon-spinner'></i></div>
                    }
                     {(table.rows as Array<any>).map((row) => {
                         table.prepareRow(row);
                         const { key, ...rowProps } = row.getRowProps();
                         return (
                             <div key={key} {...rowProps} className='table-body-row' onClick={() => navigateToItem(row.original.id)}>
                                 {(row.cells as Array<any>).map((cell) => {
                                    const {key, ...cellProps} = cell.getCellProps();
                                     return (
                                         <div key={key} {...cellProps} className='table-body-cell cell'>
                                             {cell.render('Cell')}
                                         </div>
                                     );
                                 })}
                                 <div className='table-body-cell cell actions'>
                                     <i className='icon-pencil-alt'></i>
                                     <i className='icon-trash-alt' onClick={(e) => onDelete(e, row)}></i>
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
        </div>
    );
};

export default observer(ProductList);