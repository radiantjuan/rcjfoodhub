import React from 'react';
import UniContainer from '../../hoc/UniContainer/UniContainer';
import {Col, Media, Row} from 'react-bootstrap';
import {FaTrash} from 'react-icons/fa';
import cssClasses from './CartItems.module.scss';


const CartItems = (props) => {
    const currencyFormat = (value) => {
        return new Intl.NumberFormat('en-PH', {style: 'currency', currency: 'PHP'}).format(value)
    }

    return (
        <UniContainer>
            <Media className="mb-2">

                <img
                    width={32}
                    height={32}
                    className="mr-2"
                    src={(props.cartItems.img_url) ? props.cartItems.img_url : 'https://via.placeholder.com/150'}
                    alt=""
                />
                <Media.Body className={cssClasses.FontSize}>
                    <h6 className="m-0">{props.cartItems.quantity}x {props.cartItems.title}</h6>
                    <FaTrash onClick={() => props.onCartDelete(props.cartItems.id)}
                             className={cssClasses.CursorPointer + ' text-danger'}/>
                    <Row>
                        <Col>
                            {props.cartItems.grams} gram(s)
                        </Col>
                        <Col className="text-right">
                            {currencyFormat(props.cartItems.price)}
                        </Col>
                    </Row>
                    <Row>
                        <Col>
                            {'Cost: '}
                        </Col>
                        <Col className="text-right">
                            {currencyFormat(props.cartItems.total_cost)}
                        </Col>
                    </Row>
                </Media.Body>
            </Media>
        </UniContainer>
    )
}
export default CartItems;
