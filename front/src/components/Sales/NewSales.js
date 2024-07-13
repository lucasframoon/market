import React, {useEffect, useState} from 'react';
import BackButton from "../Buttons/BackButton";
import axios from "axios";

function NewSales() {

    const [test, setTest] = useState([]);

    useEffect(() => {
        axios.get('http://localhost:8080/')
            .then(
                response => console.log(response.data)
            ).catch(error => console.error('Error fetching:', error));
    }, []);
}

export default NewSales;
