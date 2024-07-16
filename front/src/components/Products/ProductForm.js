import React, {useEffect, useState} from 'react';
import axios from 'axios';
import {useNavigate, useParams} from 'react-router-dom';
import Form from 'react-bootstrap/Form';
import Alert from "../Alerts/Alert";
import BackButton from "../Buttons/BackButton";

const ProductForm = () => {
    const {id} = useParams();
    const [name, setName] = useState('');
    const [description, setDescription] = useState('');
    const [price, setPrice] = useState('');

    const [typeId, setTypeId] = useState('');
    const [productTypes, setProductTypes] = useState([]);

    const navigate = useNavigate();
    const [successAlertMessage, setSuccessAlertMessage] = useState(null);
    const [errorAlertMessage, setErrorAlertMessage] = useState(null);

    useEffect(() => {
        let isMounted = true;

        // Get product types
        axios.get('http://localhost:8080/product-types/list')
            .then(response => {
                if (isMounted) {
                    setProductTypes(response.data);
                }
            })
            .catch(error => {
                if (isMounted) {
                    setErrorAlertMessage("Erro ao carregar tipos de produtos");
                }
                console.error('Error fetching product types:', error);
            });

        if (id) {
            axios.get(`http://localhost:8080/product/${id}`)
                .then(response => {
                    setName(response.data.name);
                    setDescription(response.data.description);
                    setPrice(response.data.price);
                    setTypeId(response.data.type_id);
                })

                .catch(error => {
                        if (isMounted) {
                            setErrorAlertMessage("Erro ao carregar os dados");
                        }
                        console.error('Error fetching product:', error)
                    }
                );
        }
        return () => {
            isMounted = false;
        };
    }, [id]);

    const handleSubmit = async (event) => {
        event.preventDefault();

        try {
            if (id) {
                const data = {name, description, price, type_id: typeId};
                await axios.put(`http://localhost:8080/product/${id}`, data);
            } else {
                const formData = new FormData();
                formData.append('name', name);
                formData.append('description', description);
                formData.append('price', price);
                formData.append('type_id', typeId);
                await axios.post('http://localhost:8080/product/new', formData);
            }
            setSuccessAlertMessage("Produto salvo com sucesso");
            setTimeout(() => {
                navigate('/products');
            }, 500);
        } catch (error) {
            setErrorAlertMessage("Erro ao salvar o produto");
            console.error('Error saving product:', error);
        }
    };

    return (
        <div className="container mt-5">
            {successAlertMessage && <Alert message={successAlertMessage} variant='primary'/>}
            {errorAlertMessage && <Alert message={errorAlertMessage} variant='danger'/>}
            <h1>{id ? "Editar Produto" : "Novo Produto"}</h1>
            <div className="buttons" style={{ display: 'flex', justifyContent: 'space-between' }}>
                <BackButton path="/products"/>
            </div>
            <form onSubmit={handleSubmit}>
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Nome</label>
                    <input
                        type="text"
                        className="form-control"
                        id="name"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                        required
                    />
                </div>
                <div className="mb-3">
                    <label htmlFor="price" className="form-label">Preço</label>
                    <input
                        type="text"
                        className="form-control"
                        id="price"
                        value={price}
                        onChange={(e) => setPrice(e.target.value)}
                        required
                    />
                </div>

                <div className="mb-3">
                    <label htmlFor="type" className="form-label">Tipo de Produto</label>
                    <Form.Select
                        id="type"
                        aria-label="Tipo de Produto"
                        value={typeId}
                        onChange={(e) => setTypeId(e.target.value)}
                        required
                    >
                        <option value="">Selecione um tipo</option>
                        {productTypes.map(type => (
                            <option key={type.id} value={type.id}>
                                {type.name}
                            </option>
                        ))}
                    </Form.Select>
                </div>

                <div className="mb-3">
                    <label htmlFor="description" className="form-label">Descrição</label>
                    <input
                        type="text"
                        className="form-control"
                        id="description"
                        value={description}
                        onChange={(e) => setDescription(e.target.value)}
                        required
                    />
                </div>
                <button type="submit" className="btn btn-primary">Salvar</button>
            </form>
        </div>
    );
};

export default ProductForm;
