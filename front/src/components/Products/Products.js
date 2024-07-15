import React, {useEffect, useState} from 'react';
import axios from "axios";
import Alert from "../Alerts/Alert";
import NewButton from "../Buttons/NewButton";
import BackButton from '../Buttons/BackButton';
import UpdateButton from "../Buttons/UpdateButton";
import DeleteButton from "../Buttons/DeleteButton";

function Products() {
    const [products, setProducts] = useState([]);

    const [successAlertMessage, setSuccessAlertMessage] = useState(null);
    const [errorAlertMessage, setErrorAlertMessage] = useState(null);

    useEffect(() => {
        let isMounted = true;
        axios.get('http://localhost:8080/product/list')
            .then(response => {
                if (isMounted) {
                    setProducts(response.data);
                }
            })
            .catch(error => {
                if (isMounted) {
                    setErrorAlertMessage("Erro ao carregar os dados");
                }
                console.error('Error fetching products:', error);
            });

        return () => {
            isMounted = false;
        };
    }, []);

    const handleDeleteClick = async (id) => {
        try {
            await axios.delete(`http://localhost:8080/product/${id}`);
            setProducts(prev => prev.filter(type => type.id !== id));
            setSuccessAlertMessage("Deletado com sucesso");
        } catch (error) {
            setErrorAlertMessage("Erro ao deletar o produto");
            console.error('Error deleting product:', error);
        }
    };

    return (
        <div className="container">
            {successAlertMessage && <Alert message={successAlertMessage} variant='primary'/>}
            {errorAlertMessage && <Alert message={errorAlertMessage} variant='danger'/>}
            <h1 className="mt-5">Produtos</h1>
            <div className="buttons" style={{ display: 'flex', justifyContent: 'space-between' }}>
                <BackButton path="/"/>
                <NewButton path="/product/form" />
            </div>
            <table className="table table-striped mt-3">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                {products.map((product, index) => (
                    <tr key={index}>
                        <td>{product.id}</td>
                        <td>{product.name}</td>
                        <td>{product.price}</td>
                        <td>{product.type_name}</td>
                        <td>{product.description}</td>
                        <td>
                            <UpdateButton path='/product/form' id={product.id}/>
                            <DeleteButton handleClick={() => handleDeleteClick(product.id)}/>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}

export default Products;
