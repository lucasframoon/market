import React, {useEffect, useState} from 'react';
import BackButton from "../Buttons/BackButton";
import axios from "axios";
import NewButton from "../Buttons/NewButton";
import UpdateButton from "../Buttons/UpdateButton";
import DeleteButton from "../Buttons/DeleteButton";
import Alert from "../Alerts/Alert";

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
            <BackButton path="/dashboard"/>
            <NewButton path="/product-types/form" />
            <h1 className="mt-5">Tipos de produtos</h1>
            <table className="table table-striped mt-3">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Taxa</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                {productTypes.map((type, index) => (
                    <tr key={index}>
                        <td>{type.name}</td>
                        <td>{type.tax_percentage}%</td>
                        <td>
                            <UpdateButton path='/product-types/form' id={type.id} />
                            <DeleteButton handleClick={() => handleDeleteClick(type.id)} />
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}

export default ProductTypes;
