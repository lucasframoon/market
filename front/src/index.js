import React from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import reportWebVitals from './reportWebVitals';
import 'bootstrap/dist/css/bootstrap.min.css';
import {createBrowserRouter, RouterProvider} from "react-router-dom";
import Dashboard from "./components/Dashboard/Dashboard";
import Products from "./components/Products/Products";
import ProductTypes from "./components/ProductTypes/ProductTypes";
import Sales from "./components/Sales/SalesList";
import NewSales from "./components/Sales/NewSales";
import ProductTypeForm from "./components/ProductTypes/ProductTypeForm";

const router = createBrowserRouter([
    {
        path: "/",
        element: <NewSales />
    },
    {
        path: "/dashboard",
        element: <Dashboard />
    },
    {
        path: "/products",
        element: <Products />
    },
    {
        path: "/product-types",
        element: <ProductTypes />
    },
    {
        path: "/product-types/form/:id?",
        element: <ProductTypeForm />
    },
    {
        path: "/sales",
        element: <Sales />
    }
])

const root = createRoot(document.getElementById('root'));

root.render(
    <React.StrictMode>
        <RouterProvider router={router} />
    </React.StrictMode>
);

reportWebVitals();
