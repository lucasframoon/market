import React, {useEffect, useState} from 'react';
import axios from "axios";
import Alert from "../Alerts/Alert";
import NewButton from "../Buttons/NewButton";
import BackButton from '../Buttons/BackButton';
import DeleteButton from "../Buttons/DeleteButton";
import {Table} from "react-bootstrap";

function Sales() {
    const [sales, setSales] = useState([]);

    const [successAlertMessage, setSuccessAlertMessage] = useState(null);
    const [errorAlertMessage, setErrorAlertMessage] = useState(null);

    useEffect(() => {
        let isMounted = true;
        axios.get('http://localhost:8080/sales/list')
            .then(response => {
                if (isMounted) {
                    setSales(response.data);
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
            await axios.delete(`http://localhost:8080/sales/${id}`);
            setSales(prev => prev.filter(type => type.id !== id));
            setSuccessAlertMessage("Deletado com sucesso");
        } catch (error) {
            setErrorAlertMessage("Erro ao deletar a venda");
            console.error('Error deleting sale:', error);
        }
    };

    const formatSaleDetails = (details) => {
        details = JSON.parse(details);
        if (details === null || details.length === 0) {
            return '';
        }

        return details.map((detail) => {
            return `${detail.product_name} (${detail.quantity})`;
        }).join(', ');
    };

    return (
        <div className="container">
            {successAlertMessage && <Alert message={successAlertMessage} variant='primary'/>}
            {errorAlertMessage && <Alert message={errorAlertMessage} variant='danger'/>}
            <h1 className="mt-5">Vendas</h1>
            <div className="buttons" style={{display: 'flex', justifyContent: 'space-between'}}>
                <BackButton path="/"/>
                <NewButton path="/sale/form"/>
            </div>
            <Table striped bordered hover className="mt-3">
                <thead>
                <tr>
                    <th style={{width: '3%'}}>#</th>
                    <th style={{width: '10%'}}>Data</th>
                    <th style={{width: '8%'}}>Valor Total</th>
                    <th style={{width: '8%'}}>Imposto total</th>
                    <th style={{width: '52%'}}>Produtos</th>
                    <th style={{width: '7%'}}>Ações</th>
                </tr>
                </thead>
                <tbody>
                {sales.map((sale, index) => (
                    <tr key={index}>
                        <td>{sale.id}</td>
                        <td>{sale.sale_date}</td>
                        <td>{sale.total_amount}</td>
                        <td>{sale.total_tax}</td>
                        <td>{formatSaleDetails(sale.details)}</td>
                        <td>
                            <DeleteButton handleClick={() => handleDeleteClick(sale.id)}/>
                        </td>
                    </tr>
                ))}
                </tbody>
            </Table>
        </div>
    );
}

export default Sales;
