import React from 'react';
import UniContainer from '../../hoc/UniContainer/UniContainer';
import {Card, Badge} from 'react-bootstrap';
import {connect} from 'react-redux';
import * as reduxActions from '../../store/actions/index';
import cssClasses from './CardSupplies.module.scss';


const CardSupplies = (props) => {
    const currencyFormat = (value) => {
        return new Intl.NumberFormat('en-PH', {style: 'currency', currency: 'PHP'}).format(value)
    }
    const productData = {
        id: props.supply_id,
        imgUrl: props.imgUrl,
        supply_name: props.supply_name,
        category: props.category,
        gram: props.gram,
        price: props.price,
        stock_count: props.stock_count
    }

    return (<UniContainer>
        <Card onClick={() => {
            props.onToggleProductModal(productData)
        }} className={cssClasses.productCard}>
            <Card.Img variant="top" src={props.imgUrl}/>
            <Card.Body className={cssClasses.cardBody}>
                <h6>{props.supply_name}</h6>
                <Badge variant="secondary">{props.category}</Badge><br/>
                <div>{props.gram} gram(s)</div>
                <div>{currencyFormat(props.price)}</div>
            </Card.Body>
        </Card>
    </UniContainer>)
}

const mapActionsToProps = (dispatch) => {
    return {
        onToggleProductModal: (productData) => {
            dispatch(reduxActions.toggleModal(productData))
        }
    }
}

export default connect(null, mapActionsToProps)(CardSupplies);
