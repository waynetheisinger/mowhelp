const BulletWithSymbol = ({expanded, expandSubLevel, attributes, title, permalink, onToggle}) => {
	let expandedClass = '';
	let collapseSymbol = '';
	let expandedSymbol = '';

	switch (attributes.symbol) {
		case "1":
			collapseSymbol = '▼';
			expandedSymbol = '►';
			break;
		case "2":
			collapseSymbol = '(–)';
			expandedSymbol = '(+)';
			break;
		case "3":
			collapseSymbol = '[–]';
			expandedSymbol = '[+]';
			break;
	}

	expandedClass = expanded && expandSubLevel ? 'expanded' : '';
	const symbol = expanded ? expandedSymbol : collapseSymbol;

	return (
		<a href={permalink} title={title} className={expandedClass} onClick={onToggle}>
			<span className="jaw_symbol">
				{symbol}
			</span>
		</a>
	);
};

export default BulletWithSymbol;
