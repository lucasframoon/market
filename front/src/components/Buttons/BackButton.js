import React from 'react';
import {useNavigate} from 'react-router-dom';
import {Button} from 'react-bootstrap';

const BackButton = ({ path }) => {
    const navigate = useNavigate();

    return (
        <Button className="back-button" variant="outline-dark" onClick={() => navigate(path)}
                style={{ width: '6 rem' }}>
            Voltar
        </Button>
    );
};

export default BackButton;
