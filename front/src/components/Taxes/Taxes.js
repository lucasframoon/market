import React from 'react';
import BackButton from "../Buttons/BackButton";

function Taxes() {
    return (
        <div className="container">
            <BackButton />
            <h1 className="mt-5">Tipos de produtos</h1>
            <table className="table table-striped mt-3">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Percentual</th>
                </tr>
                </thead>
                <tbody>
                {/* TODO tipos */}
                <tr>
                    <td>Tipo 1</td>
                    <td>10%</td>
                </tr>
                </tbody>
            </table>
        </div>
    );
}

export default Taxes;
