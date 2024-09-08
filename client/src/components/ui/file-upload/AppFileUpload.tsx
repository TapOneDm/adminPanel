import React, { useContext, useEffect, useRef, useState } from 'react';
import FileUploadService from '../../../services/FileUploadService';
import './AppFileUpload.scss';

type uploadStatusType = 'Select file' | 'uploading' | 'Done';

const AppFileUpload = (props) => {
    const inputRef = useRef();
    const { change, file } = props;

    const [selectedFile, setSelectedFile] = useState<any>(null);
    const [showControls, setShowControls] = useState<boolean>(false)
    const [progress, setProgress] = useState<number>(0);
    const [uploadStatus, setUploadStatus] = useState<uploadStatusType>('Select file');

    useEffect(() => {
        setSelectedFile(file)
    }, [])

    function handleFileChange(e) {
        let files: Array<any> = e.target.files;

        if (files && files.length > 0) {
            setSelectedFile(files[0]);
            setShowControls(true);
        }
    };

    function onChooseFile(e) {
        e.preventDefault()
        //@ts-ignore
        inputRef.current.click();
    };

    function clearFileInput() {
        //@ts-ignore
        inputRef.current.value = '';
        setSelectedFile(null);
        setProgress(0);
        setUploadStatus('Select file');
        change(null)
        setShowControls(false);
    }

    async function handleUpload(e) {
        e.preventDefault();

        if (uploadStatus === 'Done') {
            clearFileInput();
            return;
        }

        try {
            setUploadStatus('uploading');
            const formData = new FormData();
            formData.append('file', selectedFile);
            let response = await FileUploadService.uploadFile(formData, {
                onUploadProgress: (progressEvent) => {
                    const percentCompleted = Math.round((progressEvent.loaded * 100 ) / progressEvent.total);
                    setProgress(percentCompleted)
                },
                headers: {
                    'content-type': 'multipart/form-data'
                }
            })
            change(response.data.file);
            setShowControls(true);

            setUploadStatus('Done');
        } catch (e) {
            setUploadStatus('Select file');
        }
    }

    return (
        <div className='app-file-upload'>
            <input
                ref={inputRef}
                type="file"
                onChange={handleFileChange}
            />

            {!selectedFile && (
                <button className='file-btn' onClick={onChooseFile}><i className='icon-upload'></i><span>Select file</span></button>
            )}

            {selectedFile && (
                <>
                    <div className="app-file-upload-card">
                        <div className="app-file-upload-card-info">
                        <i className='icon-file-alt'></i>
                            <div style={{ flex: 1 }}>
                                <a href={selectedFile?.open_link} target='blank' className='app-file-upload-card-info-name'>{selectedFile.name}</a>
                                {showControls && (
                                    <div className='app-file-upload-card-info-progress-bg'>
                                        <div className="progress" style={{ width: `${progress}%`}}></div>
                                    </div>
                                )}
                            </div>

                            <i onClick={clearFileInput} className='icon-times close'></i>
                        </div>
                    </div>
                        {showControls && (
                            <button
                                className='button upload'
                                onClick={handleUpload}
                            >Upload</button>
                        )
                    }
                </>
            )}
        </div>
    );
};

export default AppFileUpload;