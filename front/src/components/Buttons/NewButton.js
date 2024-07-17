import React from 'react';
import {Button} from 'react-bootstrap';
import {useNavigate} from "react-router-dom";

const NewButton = ({ path }) => {
    const navigate = useNavigate();

    const handleClick = () => {
        navigate(path);
    };

    return (
        <Button variant="primary" onClick={handleClick} style={{ width: '6 rem' }}>
            Novo
        </Button>
    );
};

export default NewButton;
