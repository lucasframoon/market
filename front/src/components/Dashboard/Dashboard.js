import React from 'react';
import './Dashboard.css';
import { Link } from 'react-router-dom';
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
                        <Card.Title>Impostos</Card.Title>
                        <Card.Text>
                            Configure os impostos aplicáveis aos produtos
                        </Card.Text>
                        <Link to="/taxes" className="btn btn-primary">
                            Ver Impostos
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