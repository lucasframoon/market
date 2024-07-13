import React from 'react';
import {Button} from 'react-bootstrap';

const DeleteButton = ({ handleClick }) => {
    return (
        <Button variant='danger' onClick={handleClick}>
            Excluir
        </Button>
    );
};

export default DeleteButton;