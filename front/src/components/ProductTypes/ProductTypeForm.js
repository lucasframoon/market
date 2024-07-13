import React, {useEffect, useState} from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import axios from 'axios';

const ProductTypeForm = () => {
    const [name, setName] = useState('');
    const [taxPercentage, setTaxPercentage] = useState('');
    const navigate = useNavigate();
    const { id } = useParams();

    useEffect(() => {
        if (id) {
            axios.get(`http://localhost:8080/product-types/${id}`)
                .then(response => {
                    setName(response.data.name);
                    setTaxPercentage(response.data.tax_percentage);
                })
                .catch(error => console.error('Error fetching product type:', error));
        }
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
            navigate('/product-types');
        } catch (error) {
            console.error('Error creating product type:', error);
        }
    };

    return (
        <div className="container mt-5">
            <h1>Tipo de Produto</h1>
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
                    <label htmlFor="taxPercentage" className="form-label">Taxa (%)</label>
                    <input
                        type="text"
                        className="form-control"
                        id="taxPercentage"
                        value={taxPercentage}
                        onChange={(e) => setTaxPercentage(e.target.value)}
                        required
                    />
                </div>
                <button type="submit" className="btn btn-primary">Salvar</button>
            </form>
        </div>
    );
};

export default ProductTypeForm;
