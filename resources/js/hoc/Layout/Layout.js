import React  from 'react';
import UniContainer from '../UniContainer/UniContainer';
import { Container } from 'react-bootstrap';
import cssClasses from './Layout.module.scss';

const Layout = (props) => {
    return (
        <UniContainer>
            <Container fluid className={cssClasses.container_max_width}>
                {props.children}
            </Container>
        </UniContainer>
    )
}

export default Layout;
