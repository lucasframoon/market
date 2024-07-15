import React from 'react';
import {createRoot} from 'react-dom/client';
import './index.css';
import reportWebVitals from './reportWebVitals';
import 'bootstrap/dist/css/bootstrap.min.css';
import {createBrowserRouter, RouterProvider} from "react-router-dom";
import Dashboard from "./components/Dashboard/Dashboard";
import Products from "./components/Products/Products";
import ProductForm from "./components/Products/ProductForm";
import ProductTypes from "./components/ProductTypes/ProductTypes";
import ProductTypeForm from "./components/ProductTypes/ProductTypeForm";
import Sales from "./components/Sales/Sales";
import SalesForm from "./components/Sales/SalesForm";

const router = createBrowserRouter([
    {
        path: "/",
        element: <Dashboard/>
    },
    {
        path: "/products",
        element: <Products/>
    },
    {
        path: "/product/form/:id?",
        element: <ProductForm/>
    },
    {
        path: "/product-types",
        element: <ProductTypes/>
    },
    {
        path: "/product-type/form/:id?",
        element: <ProductTypeForm/>
    },
    {
        path: "/sales",
        element: <Sales/>
    },
    {
        path: "/sale/form/:id?",
        element: <SalesForm/>
    },
])

const root = createRoot(document.getElementById('root'));

root.render(
    <React.StrictMode>
        <RouterProvider router={router}/>
    </React.StrictMode>
);

reportWebVitals();
