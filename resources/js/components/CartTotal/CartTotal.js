import React, { useState } from 'react';
import UniContainer from '../../hoc/UniContainer/UniContainer';
import { Col, Row, InputGroup, Button, FormControl, Card, Alert } from 'react-bootstrap';
import cssClasses from './CartTotal.module.scss';


const CartItems = (props) => {
    const [promocode, setPromocode] = useState('');

    const onPromoCodeChange = (element) => {
        setPromocode(element.target.value);
    }

    const onPromoCodeRemove = () => {
        props.onPromoCodeApply('remove');
    }

    const currencyFormat = (value) => {
        return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value)
    }

    const promoCodeCoverage = props.promoCodeSetup.promo_code_coverage.map((items) => {
        return (
            <Row key={items.title}>
                <Col className="col-7">
                    <small>
                        {items.title} ({-items.total_discount})
                    </small>
                </Col>
                <Col className="text-right">
                    <small>

                        {currencyFormat(items.new_costs)}

                    </small>
                </Col>
            </Row>
        )
    });
    let promoCodeException = '';
    let promoCodeExceptionHeader = '';
    if (props.promoCodeSetup.promo_code_exceptions) {
        promoCodeException = props.promoCodeSetup.promo_code_exceptions.map((items) => {
            promoCodeExceptionHeader = (<small>Not included:</small>);
            return (
                <Row key={items.title}>
                    <Col className="col-7">
                        <small>
                            <strike>
                                {items.title}
                            </strike>
                        </small>
                    </Col>
                </Row>
            )
        });
    }

    return (
        <UniContainer>
            <Row>
                <Col>
                    <b>{'Total Item(s):'}</b>
                </Col>
                <Col className="text-right">
                    {props.cartTotal.total_items}
                </Col>
            </Row>
            <Row>
                <Col>
                    <b>{'Total Cost(s):'}</b>
                </Col>
                <Col className="text-right">

                    {currencyFormat(props.cartTotal.total_costs)}

                </Col>
            </Row>
            <hr />
            <Row>
                <Col>
                    <InputGroup className="mb-3 pt-2">
                        <FormControl
                            placeholder="Promo Code"
                            aria-label="Promo Code"
                            aria-describedby="basic-addon2"
                            onChange={(element) => { onPromoCodeChange(element); }}
                        />
                        <InputGroup.Append>
                            <Button variant="primary" onClick={() => { props.onPromoCodeApply(promocode) }}>Apply</Button>
                        </InputGroup.Append>
                    </InputGroup>
                </Col>
            </Row>
            <Row>
                <Col>
                    {(props.promoCodeSetup.new_amount_to_be_paid <= 0) ? (
                        <Alert variant="danger">
                            Promo code is not applied you have to add more in your cart!
                        </Alert>
                    ) : ''}

                </Col>
            </Row>
            <Row>
                <Col>
                    <b>{'Promo Code:'}</b><br />
                    <small className={cssClasses.Pointer + ' text-danger'} onClick={() => { onPromoCodeRemove() }}>remove promocode</small>
                </Col>
                <Col className="text-right">
                    {props.promoCodeSetup.promo_code}
                </Col>
            </Row>
            {promoCodeCoverage}
            {promoCodeExceptionHeader}
            {promoCodeException}
            <Row>
                <Col>
                    <strong>Total Discount</strong>
                </Col>
                <Col className="text-right text-success">
                    {(props.promoCodeSetup.promo_code_total_discount) ? currencyFormat(-props.promoCodeSetup.promo_code_total_discount) : ''}
                </Col>
            </Row>
            <hr />
            <Row>
                <Col>
                    <Card.Title className="m-0">
                        Total to be paid: {currencyFormat((props.promoCodeSetup.new_amount_to_be_paid > 0) ? props.promoCodeSetup.new_amount_to_be_paid : props.cartTotal.total_to_be_paid)}
                    </Card.Title>
                </Col>
            </Row>
        </UniContainer>
    );
}

export default CartItems;
