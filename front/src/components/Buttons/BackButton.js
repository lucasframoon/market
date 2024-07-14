import React from 'react';
import { useNavigate } from 'react-router-dom';
import { Button } from 'react-bootstrap';

const BackButton = ({ path }) => {
    const navigate = useNavigate();

    return (
        <Button variant="secondary" onClick={() => navigate(path)}
                style={{ position: 'absolute', top: '30px', left: '20px', width: '70px' }}>
            Voltar
        </Button>
    );
};

export default BackButton;
