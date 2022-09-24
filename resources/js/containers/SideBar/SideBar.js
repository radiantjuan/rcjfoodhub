import React, {useEffect} from 'react';
import { Card, Button } from 'react-bootstrap';
import { FaCartPlus } from 'react-icons/fa';
import UniContainer from '../../hoc/UniContainer/UniContainer';
import CartItems from '../../components/CartItems/CartItems';
import CartTotal from '../../components/CartTotal/CartTotal';
import { connect } from 'react-redux';
import * as reduxActions from '../../store/actions/index';
import classes from './SideBar.module.scss';


const SideBar = (props) => {
    useEffect(() => {
        props.onCartInit();
    }, []);

    const cartItems = props.cart_items.map((cart) => {
        return (<CartItems key={cart.id} cartItems={cart} onCartDelete={(id) => { props.onCartDelete(id) }}/>)
    });

    return (
        <UniContainer>
            <div className="side-bar mt-2">
                <Card>
                    <Card.Header>
                        <Card.Title className="m-0">
                            <FaCartPlus /> Cart
                        </Card.Title>
                    </Card.Header>
                    <Card.Body className={classes.overflowscroll}>
                        {(cartItems !== '') ? cartItems : 'There is no item in your cart.'}
                    </Card.Body>
                    <Card.Footer className={classes.footer_small_foot}>
                        <CartTotal promoCodeSetup={props.promo_code_setup} cartTotal={props.cart_total} onPromoCodeApply={(promo_code)=>props.onPromoCodeApply(promo_code)}/>
                    </Card.Footer>
                </Card>
                <Button href="/franchisee/shipping" className="btn btn-success btn-lg w-100 mt-2" disabled={(props.cart_total.total_to_be_paid <= 0)}> Checkout</Button>
            </div>
        </UniContainer>
    )
}

const mapStateToProps = (state) => {
    return {
        cart_items: state.cart,
        cart_total: state.cart_total,
        promo_code_setup: state.promo_code
    }
}

const mapDispatchToProps = dispatch => {
    return {
        onCartInit: () => dispatch(reduxActions.getAllBasketItemsInDB()),
        onCartDelete: (id) => dispatch(reduxActions.deleteItemInCartDispatcher(id)),
        onPromoCodeApply: (promo_code) => dispatch(reduxActions.onPromoCodeApplyDispatcher(promo_code))
    };
}

export default connect(mapStateToProps,mapDispatchToProps)(SideBar);
