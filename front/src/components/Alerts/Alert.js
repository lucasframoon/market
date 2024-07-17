import React, {useEffect, useState} from 'react';
import {Alert as BootstrapAlert} from 'react-bootstrap';

function Alert({ message, variant }) {
    const [showAlert, setShowAlert] = useState(true);

    useEffect(() => {
        const timer = setTimeout(() => {
            setShowAlert(false);
        }, 2000);

        return () => clearTimeout(timer);
    }, []);

    return (
        <>
            {showAlert && (
                <BootstrapAlert variant={variant}>
                    {message}
                </BootstrapAlert>
            )}
        </>
    );
}

export default Alert;
