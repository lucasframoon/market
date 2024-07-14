import React from 'react';
import {Button} from 'react-bootstrap';
import {useNavigate} from "react-router-dom";

const UpdateButton = ({ path, id }) => {

    const navigate = useNavigate();

    const handleClick = () => {
        navigate(path+'/'+id);
    }
    
    return (
        <Button variant='info' onClick={handleClick} style={{ marginRight: '10px' }}>
            Editar
        </Button>
    );
};

export default UpdateButton;
