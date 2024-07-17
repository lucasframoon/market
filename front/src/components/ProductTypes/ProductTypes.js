import React, {useEffect, useState} from 'react';
import axios from "axios";
import Alert from "../Alerts/Alert";
import NewButton from "../Buttons/NewButton";
import BackButton from "../Buttons/BackButton";
import UpdateButton from "../Buttons/UpdateButton";
import DeleteButton from "../Buttons/DeleteButton";
import {Table} from "react-bootstrap";

function ProductTypes() {
    const [productTypes, setProductTypes] = useState([]);

    const [successAlertMessage, setSuccessAlertMessage] = useState(null);
    const [errorAlertMessage, setErrorAlertMessage] = useState(null);

    useEffect(() => {
        let isMounted = true;
        axios.get('http://localhost:8080/product-types/list')
            .then(response => {
                if (isMounted) {
                    setProductTypes(response.data);
                }
            })
            .catch(error => {
                if (isMounted) {
                    setErrorAlertMessage("Erro ao carregar os dados");
                }
                console.error('Error fetching products types:', error);
            });

        return () => {
            isMounted = false;
        };
    }, []);

    const handleDeleteClick = async (id) => {
        try {
            await axios.delete(`http://localhost:8080/product-types/${id}`);
            setProductTypes(prev => prev.filter(type => type.id !== id));
            setSuccessAlertMessage("Deletado com sucesso");
        } catch (error) {
            setErrorAlertMessage("Erro ao deletar tipo de produto");
            console.error('Error deleting product type:', error);
        }
    };

    return (
        <div className="container">
            {successAlertMessage && <Alert message={successAlertMessage} variant='primary'/>}
            {errorAlertMessage && <Alert message={errorAlertMessage} variant='danger'/>}
            <h1 className="mt-5">Tipos de produtos</h1>
            <div className="buttons" style={{display: 'flex', justifyContent: 'space-between'}}>
                <BackButton path="/"/>
                <NewButton path="/product-type/form"/>
            </div>
            <Table striped bordered hover className="mt-3">
                <thead>
                <tr>
                    <th style={{width: '30%'}}>Nome</th>
                    <th style={{width: '30%'}}>Taxa</th>
                    <th style={{width: '10%'}}>Ações</th>
                </tr>
                </thead>
                <tbody>
                {productTypes.map((type, index) => (
                    <tr key={index}>
                        <td>{type.name}</td>
                        <td>{type.tax_percentage}%</td>
                        <td>
                            <UpdateButton path='/product-type/form' id={type.id}/>
                            <DeleteButton handleClick={() => handleDeleteClick(type.id)}/>
                        </td>
                    </tr>
                ))}
                </tbody>
            </Table>
        </div>
    );
}

export default ProductTypes;
