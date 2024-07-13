import React, {useEffect, useState} from 'react';
import BackButton from "../Buttons/BackButton";
import axios from "axios";
import NewButton from "../Buttons/NewButton";
import UpdateButton from "../Buttons/UpdateButton";
import DeleteButton from "../Buttons/DeleteButton";
import {useNavigate} from "react-router-dom";


function ProductTypes() {

    const navigate = useNavigate();
    const [productTypes, setProductTypes] = useState([]);

    useEffect(() => {
        axios.get('http://localhost:8080/product-types/list')
            .then(
                response => setProductTypes(response.data)
            ).catch(
                error => console.error('Error fetching products types:', error)
            );
    }, []);

    const handleDeleteClick = async (id) => {

        try {
            await axios.put('http://localhost:8080/product-types/delete/${id}');
            navigate('/product-types');
        } catch (error) {
            console.error('Error update product type:', error);
        }
    };


    return (
        <div className="container">
            <BackButton />
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
