import React from 'react';
import './Dashboard.css';
import {Link} from 'react-router-dom';
import Card from 'react-bootstrap/Card';
import productImage from '../../assets/images/product.png';
import taxesImage from '../../assets/images/taxes.png';
import sellsImage from '../../assets/images/sells.png';

const Dashboard = () => {
    return (
        <div className="container mt-5">
            <h1 className="mb-4">Dashboard</h1>
            <div className="row">
                <Card className="Card">
                    <Card.Img className="img" variant="top" src={productImage} />
                    <Card.Body>
                        <Card.Title>Produtos</Card.Title>
                        <Card.Text>
                            Gerencie os produtos disponíveis
                        </Card.Text>
                        <Link to="/products" className="btn btn-primary">
                            Ver Produtos
                        </Link>
                    </Card.Body>
                </Card>

                <Card className="Card">
                    <Card.Img className="img" variant="top" src={taxesImage} />
                    <Card.Body>
                        <Card.Title>Tipos de produtos e impostos</Card.Title>
                        <Card.Text>
                            Configure os tipos de produtos e os respectivos impostos
                        </Card.Text>
                        <Link to="/product-types" className="btn btn-primary">
                            Ver Tipos/Impostos
                        </Link>
                    </Card.Body>
                </Card>

                <Card className="Card">
                    <Card.Img className="img" variant="bottom" src={sellsImage} />
                    <Card.Body>
                        <Card.Title>Vendas</Card.Title>
                        <Card.Text>
                            Registre as vendas realizadas
                        </Card.Text>
                        <Link to="/sales" className="btn btn-primary">
                            Ver Vendas
                        </Link>
                    </Card.Body>
                </Card>
            </div>
        </div>
    );
}

export default Dashboard;