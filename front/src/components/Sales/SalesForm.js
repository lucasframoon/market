import React, {useEffect, useState} from 'react';
import axios from "axios";
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';
import Col from 'react-bootstrap/Col';
import Row from 'react-bootstrap/Row';
import BackButton from "../Buttons/BackButton";
import {useNavigate, useParams} from "react-router-dom";
import {Table} from "react-bootstrap";
import Alert from "../Alerts/Alert";

function SalesForm() {
    const {id} = useParams();
    const [saleDate, setSaleDate] = useState('');
    const [totalAmount, setTotalAmount] = useState(0);
    const [totalTax, setTotalTax] = useState(0);

    const [selectedProductId, setSelectedProductId] = useState('');
    const [productQuantity, setProductQuantity] = useState(1);
    const [productList, setProductList] = useState([]);
    const [products, setProducts] = useState([]);

    const navigate = useNavigate();
    const [successAlertMessage, setSuccessAlertMessage] = useState(null);
    const [errorAlertMessage, setErrorAlertMessage] = useState(null);

    useEffect(() => {
        let isMounted = true;

        axios.get('http://localhost:8080/product/list')
            .then((response) => {
                if (isMounted) {
                    setProductList(response.data);
                }
            })
            .catch((error) => {
                console.error('Error fetching products:', error);
            });

        return () => {
            isMounted = false;
        };
    }, [id]);

    const handleSubmit = async (event) => {
        event.preventDefault();

        if (products.length === 0) {
            setErrorAlertMessage("Adicione pelo menos um produto à venda.");
            return;
        }

        try {
            const formData = new FormData();
            formData.append('sale_date', saleDate);
            formData.append('products', JSON.stringify(products));
            await axios.post('http://localhost:8080/sales/new', formData);

            setSuccessAlertMessage("Venda salva com sucesso");
            setTimeout(() => {
                navigate('/sales');
            }, 500);
        } catch (error) {
            setErrorAlertMessage("Erro ao registrar venda");
            console.error('Error saving sale:', error);
        }
    };

    const handleAddProduct = () => {
        if (selectedProductId === '') {
            setErrorAlertMessage("Selecione um produto válido");
            return;
        }

        if (productQuantity < 1) {
            setErrorAlertMessage("A quantidade deve ser maior que zero");
            return;
        }

        const product = productList.find(product => product.id === parseInt(selectedProductId));
        if (!product) {
            setErrorAlertMessage("Produto não encontrado");
            return;
        }

        const existingProduct = products.find(product => product.product_id === parseInt(selectedProductId));
        if (existingProduct) {
            existingProduct.quantity += productQuantity;
        } else {
            products.push({
                product_id: product.id,
                name: product.name,
                price: product.price,
                tax_percentage: product.tax_percentage,
                quantity: productQuantity
            });
        }

        setProducts([...products]);
        calculateTotals();
        setSelectedProductId('');
        setProductQuantity(1);
    };

    const handleRemoveProduct = (productId) => {
        const updatedProducts = products.filter(product => product.product_id !== productId);
        setProducts(updatedProducts);
        calculateTotals();
    };

    const calculateTotals = () => {
        let totalAmount = 0;
        let totalTax = 0;

        products.forEach(product => {
            const productTotal = product.price * product.quantity;
            const productTax = (product.price * product.tax_percentage / 100) * product.quantity;

            totalAmount += productTotal;
            totalTax += productTax;
        });
        setTotalAmount(totalAmount);
        setTotalTax(totalTax);
    };

    return (
        <div className="container mt-5">
            {successAlertMessage && <Alert message={successAlertMessage} variant='primary'/>}
            {errorAlertMessage && <Alert message={errorAlertMessage} variant='danger'/>}
            <BackButton path="/sales"/>
            <h1>{id ? "Detalhes da venda" : "Registrar venda"}</h1>
            <Form onSubmit={handleSubmit}>
                <Row className="mb-3">
                    <Col>
                        <Form.Group controlId="saleDate">
                            <Form.Label>Data da Venda</Form.Label>
                            <Form.Control
                                type="date"
                                value={saleDate}
                                onChange={(e) => setSaleDate(e.target.value)}
                                required
                            />
                        </Form.Group>
                    </Col>
                </Row>
                <Row className="mb-3">
                    <Col>
                        <Form.Group controlId="productSelect">
                            <Form.Label>Produto</Form.Label>
                            <Form.Select
                                value={selectedProductId}
                                onChange={(e) => setSelectedProductId(e.target.value)}
                            >
                                <option value="">Selecione um produto</option>
                                {productList.map(product => (
                                    <option key={product.id} value={product.id}>
                                        {product.name}
                                    </option>
                                ))}
                            </Form.Select>
                        </Form.Group>
                    </Col>
                    <Col>
                        <Form.Group controlId="productQuantity">
                            <Form.Label>Quantidade</Form.Label>
                            <Form.Control
                                type="number"
                                value={productQuantity}
                                onChange={(e) => setProductQuantity(parseInt(e.target.value))}
                                min="1"
                                required
                            />
                        </Form.Group>
                    </Col>
                    <Col className="align-self-end">
                        <Button variant="primary" onClick={handleAddProduct}>Adicionar Produto</Button>
                    </Col>
                </Row>
                <Table striped bordered hover className="mt-3">
                    <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Valor Total</th>
                        <th>Imposto</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    {products.map((product, index) => (
                        <tr key={index}>
                            <td>{product.name}</td>
                            <td>{product.quantity}</td>
                            <td>{product.price}</td>
                            <td>{(product.price * product.quantity).toFixed(2)}</td>
                            <td>{((product.price * product.tax_percentage / 100) * product.quantity).toFixed(2)}</td>
                            <td>
                                <Button variant="danger"
                                        onClick={() => handleRemoveProduct(product.product_id)}>Remover</Button>
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </Table>
                <Row className="mt-3">
                    <Col>
                        <h5>Valor Total: {totalAmount.toFixed(2)}</h5>
                        <h5>Imposto Total: {totalTax.toFixed(2)}</h5>
                    </Col>
                </Row>
                <Button variant="primary" type="submit" className="mt-3">
                    Finalizar Venda
                </Button>
            </Form>
        </div>
    );
}

export default SalesForm;
