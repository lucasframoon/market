import React from 'react';
import BackButton from "../Buttons/BackButton";

function SalesList() {
    return (
        <div className="container">
            <BackButton />
            <h1 className="mt-5">Vendas</h1>
            <table className="table table-striped mt-3">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Produtos</th>
                </tr>
                </thead>
                <tbody>
                {/* TODO sales */}
                <tr>
                    <td>1</td>
                    <td>12/07/2024</td>
                    <td>R$ 100,00</td>
                    <td>Produto1, Produto2</td>
                </tr>
                </tbody>
            </table>
        </div>
    );
}

export default SalesList;
