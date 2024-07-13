import React from 'react';
import { Button } from 'react-bootstrap';
import {useNavigate} from "react-router-dom";

const NewButton = ({ path }) => {
    const navigate = useNavigate();

    const handleClick = () => {
        navigate(path);
    };

    return (
        <Button variant="primary" onClick={handleClick}
                style={{ position: 'absolute', top: '80px', left: '20px', width: '70px' }}>
            Novo
        </Button>
    );
};

export default NewButton;
