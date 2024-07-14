import React from 'react';
import BackButton from '../Buttons/BackButton';

function Products() {
    return (
        <div className="container">
            <h1 className="mt-5">Produtos</h1>
            <BackButton path="/dashboard"/>
            <table className="table table-striped mt-3">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                </tr>
                </thead>
                <tbody>
                {/* TODO products */}
                <tr>
                    <td>1</td>
                    <td>Produto 1</td>
                    <td>R$ 10,00</td>
                    <td>Tipo 1</td>
                    <td>Descrição do produto</td>
                </tr>
                </tbody>
            </table>
        </div>
    );
}

export default Products;
