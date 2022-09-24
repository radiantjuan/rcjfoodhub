import React, {useState} from 'react';
import UniContainer from '../../hoc/UniContainer/UniContainer';
import {Modal, Button, Container, Row, Col, Form, Badge} from 'react-bootstrap';

const ItemInfoModal = (props) => {
    const [quantity, setQuantity] = useState(1);
    const onQuantityChange = (element) => {
        setQuantity(parseInt(element.target.value));
    }

    const currencyFormat = (value) => {
        return new Intl.NumberFormat('en-PH', {style: 'currency', currency: 'PHP'}).format(value)
    }

    return (
        <UniContainer>
            <Modal.Header className="p-0">
                <img src={props.imgUrl} alt={props.title} className="w-100"/>
            </Modal.Header>
            <Modal.Body className="p-2">
                <Container className="px-2">
                    <Row>
                        <Col>
                            <h4>{props.title}</h4>
                        </Col>
                    </Row>
                    <Row>
                        <Col>
                            <Badge variant="secondary">{props.category}</Badge>
                        </Col>
                    </Row>
                    <Row>
                        <Col>
                            {props.gram} gram(s)
                        </Col>
                        <Col>
                            {currencyFormat(props.price)}
                        </Col>
                    </Row>
                    <Row className="pt-2">
                        <Col>
                            <Form.Group controlId="exampleForm.ControlSelect1" onChange={(element) => {
                                onQuantityChange(element);
                            }}>
                                <Form.Label>Choose Quantity</Form.Label>
                                <Form.Control size="sm" type="number" min={1} defaultValue={1}
                                              placeholder="Enter Quantity"/>
                            </Form.Group>
                        </Col>
                    </Row>
                </Container>
            </Modal.Body>
            <Modal.Footer>
                <Button size="sm" variant="secondary" onClick={() => {
                    props.onModalCloseEvent()
                }}>Close</Button>
                <Button size="sm" variant="primary" onClick={() => {
                    props.onAddToCart({id: props.id, quantity: quantity});
                    props.onModalCloseEvent()
                }}>Add to Cart</Button>
            </Modal.Footer>
        </UniContainer>
    )
}

export default ItemInfoModal;
