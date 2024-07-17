import React from 'react';
import {Button} from 'react-bootstrap';

const DeleteButton = ({ handleClick }) => {
    return (
        <Button variant='danger' onClick={handleClick} style={{width: '4.5rem' }}>
            Excluir
        </Button>
    );
};

export default DeleteButton;
