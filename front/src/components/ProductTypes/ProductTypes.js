import React, {useEffect, useState} from 'react';
import BackButton from "../Buttons/BackButton";
import axios from "axios";

function ProductTypes() {

    const [productTypes, setProductTypes] = useState([]);

    useEffect(() => {
        axios.get('http://localhost:8080/product-types/list')
            .then(
                response => setProductTypes(response.data)
            ).catch(
                error => console.error('Error fetching products types:', error)
            );
    }, []);


    return (
        <div className="container">
            <BackButton />
            <h1 className="mt-5">Tipos de produtos</h1>
            <table className="table table-striped mt-3">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Taxa</th>
                </tr>
                </thead>
                <tbody>
                {productTypes.map((type, index) => (
                    <tr key={index}>
                        <td>{type.name}</td>
                        <td>{type.tax_percentage}%</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}

export default ProductTypes;
