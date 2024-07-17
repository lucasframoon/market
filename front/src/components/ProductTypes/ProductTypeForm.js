import React, {useEffect, useState} from 'react';
import {useNavigate, useParams} from 'react-router-dom';
import axios from 'axios';
import Alert from "../Alerts/Alert";
import BackButton from "../Buttons/BackButton";
import Form from "react-bootstrap/Form";
import Button from "react-bootstrap/Button";
import Col from "react-bootstrap/Col";
import Row from "react-bootstrap/Row";


const ProductTypeForm = () => {
    const [name, setName] = useState('');
    const [taxPercentage, setTaxPercentage] = useState('');
    const navigate = useNavigate();
    const {id} = useParams();

    const [successAlertMessage, setSuccessAlertMessage] = useState(null);
    const [errorAlertMessage, setErrorAlertMessage] = useState(null);

    useEffect(() => {
        let isMounted = true;
        if (id) {
            axios.get(`http://localhost:8080/product-types/${id}`)
                .then(response => {
                    setName(response.data.name);
                    setTaxPercentage(response.data.tax_percentage);
                })
                .catch(error => {
                        if (isMounted) {
                            setErrorAlertMessage("Erro ao carregar os dados");
                        }
                        console.error('Error fetching product type:', error)
                    }
                );
        }
        return () => {
            isMounted = false;
        };
    }, [id]);

    const handleSubmit = async (event) => {
        event.preventDefault();

        if (taxPercentage < 0 || taxPercentage > 100) {
            alert('A taxa deve estar entre 0 e 100.');
            return;
        }

        try {
            if (id) {
                await axios.put(`http://localhost:8080/product-types/${id}`, {name, tax_percentage: taxPercentage});
            } else {
                const formData = new FormData();
                formData.append('name', name);
                formData.append('tax_percentage', taxPercentage);
                await axios.post('http://localhost:8080/product-types/new', formData);
            }
            setSuccessAlertMessage("Tipo de produto salvo com sucesso");

            setTimeout(() => {
                navigate('/product-types');
            }, 500);
        } catch (error) {
            setErrorAlertMessage("Erro ao salvar o tipo de produto");
            console.error('Error creating product type:', error);
        }
    };

    return (
        <div className="container mt-5">
            {successAlertMessage && <Alert message={successAlertMessage} variant='primary'/>}
            {errorAlertMessage && <Alert message={errorAlertMessage} variant='danger'/>}
            <h1>{id ? "Editar Tipo de Produto" : "Novo Tipo de Produto"}</h1>
            <div className="buttons" style={{display: 'flex', justifyContent: 'space-between'}}>
                <BackButton path="/product-types"/>
            </div>
            <Form onSubmit={handleSubmit}>

                <Row className="mb-3">
                    <Col>
                        <Form.Group controlId="name">
                            <Form.Label>Nome</Form.Label>
                            <Form.Control
                                type="text"
                                value={name}
                                onChange={(e) => setName(e.target.value)}
                                required
                            />
                        </Form.Group>
                    </Col>
                </Row>
                <Row className="mb-3">
                    <Col>
                        <Form.Group controlId="taxPercentage">
                            <Form.Label>Taxa (%)</Form.Label>
                            <Form.Control
                                type="number"
                                min="0"
                                max="100"
                                step="0.01"
                                value={taxPercentage}
                                onChange={(e) => setTaxPercentage(e.target.value)}
                                required
                            />
                        </Form.Group>
                    </Col>
                </Row>
                <Button variant="primary" type="submit" className="mt-3">
                    Salvar
                </Button>
            </Form>
        </div>
    );
};

export default ProductTypeForm;
