import React, { useEffect, useState } from 'react';

import AppButton from "../button/AppButton" 

import "./Modal.scss";
import { createPortal } from 'react-dom';

export type ModalType = 'alert' | 'confirm';

const portal = document.querySelector('#portal');

const Modal = (props) => {
    const [loading, setLoading] = useState<boolean>(false);
    const { type, data, active, setActive, onConfirm } = props
    const { title, titleIcon, text } = data;

    function confirm() {
        setLoading(true);
        onConfirm().finally(() => {
            setLoading(false);
            setActive(false);
        })
    }

    function close() {
        setActive(false)
    }

    return createPortal(<>
            {active && (
                <div className='modal-wrapper'>
                    <div className="modal" style={{maxWidth: "500px"}}>
                        <div className="modal-title"><i className={`icon-${titleIcon}`}></i>{title}</div>
                        <div className="modal-text">{text}</div>
                        <div className="modal-actions">
                            {(type as ModalType) === 'confirm' &&
                                <AppButton icon={'check'} onClick={confirm} loading={loading}>Confirm</AppButton>
                            }
                            <AppButton icon={'times'} onClick={close}>Close</AppButton>
                        </div>
                    </div>
                </div>
            )}
        </>, portal);

};

export default Modal;
