import React, { Component } from 'react';
import { Dropdown, DropdownButton } from 'react-bootstrap';
import List from './List';

class FilteredList extends Component {
  constructor(props) {
    super(props);

    this.state = {
      search: '',
      type: 'All'   // All, Fruit, Vegetable
    };
  }

  // Called whenever the user types in the search bar
  onSearch = (event) => {
    this.setState({
      search: event.target.value
    });
  };

  // Called when a dropdown option is selected
  onSelectType = (eventKey) => {
    this.setState({
      type: eventKey
    });
  };

  // Does this item match the search text?
  matchesSearch = (item) => {
    const searchText = this.state.search.toLowerCase();

    if (searchText === '') {
      return true;
    }

    return item.name.toLowerCase().includes(searchText);
  };

  // Does this item match the selected type?
  matchesFilterType = (item) => {
    const { type } = this.state;

    if (type === 'All') {
      return true;
    }

    return item.type === type;
  };

  // Combined filter: must pass both search and type filters
  filterItem = (item) => {
    return this.matchesSearch(item) && this.matchesFilterType(item);
  };

  render() {
    const { type } = this.state;

    return (
      <div className="filter-list">
        <div className="filter-controls">
          <DropdownButton
            id="type-filter-dropdown"
            title={type}
            onSelect={this.onSelectType}
          >
            <Dropdown.Item eventKey="All">All</Dropdown.Item>
            <Dropdown.Item eventKey="Fruit">Fruit</Dropdown.Item>
            <Dropdown.Item eventKey="Vegetable">Vegetables</Dropdown.Item>
          </DropdownButton>

          <input
            type="text"
            placeholder="Search"
            onChange={this.onSearch}
          />
        </div>

        <List items={this.props.items.filter(this.filterItem)} />
      </div>
    );
  }
}

export default FilteredList;
