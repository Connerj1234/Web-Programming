import React, { Component } from 'react';

class Counter extends Component {
    constructor(props) {
        super(props);

        this.state = {
            count: 5
        };
    }

    incrementCount = () => {
        // Use setState and the previous state to increment
        this.setState(prevState => ({
            count: prevState.count + 1
        }));
    };

    render() {
        return (
            <div className="counter">
                <p>Count: {this.state.count}</p>

                <button onClick={this.incrementCount}>Increment</button>
            </div>
        );
    }
}

export default Counter;
