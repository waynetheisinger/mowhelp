import {useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {__} from "@wordpress/i18n";
import BulletWithSymbol from "./BulletWithSymbol";
import DisplayMonth from "./DisplayMonth";

const DisplayYear = ({attributes, currentConfig, yearObj}) => {
	const [loading, setLoading] = useState(true);
	const [months, setMonths] = useState([]);

	const loadMonths = (e) => {
		e.preventDefault();
		setLoading(true);

		const params = new URLSearchParams();
		params.append('type', attributes.type || 'post');
		params.append('onlycat', attributes.onlycategory || '');
		params.append('exclusionType', attributes.include_or_exclude);
		params.append('cats', attributes.categories);

		apiFetch({path: `/jalw/v1/months?${params.toString()}`}).then((data) => {
			setMonths(data.months);
			setLoading(false);
		});
	};

	const currentYear = new Date().getFullYear();
	const expandCurrentDate = 'current' === attributes.expand || 'current_date' === attributes.expand;
	const expandCurrentPost = 'current' === attributes.expand || 'current_post' === attributes.expand;
	const expandByPostDate = yearObj.year === currentConfig.curPostYear && expandCurrentPost;
	const expandByCurDate = yearObj.year === currentYear && expandCurrentDate;
	const expandYear = expandByCurDate || expandByPostDate || 'all' === attributes.expand;

	const showPosts = attributes.showcount ? ('(' + yearObj.posts + ')') : '';
	let lnkYear = <span className="year">{yearObj.year} {showPosts}</span>;
	let handleToggle = loadMonths;

	if (attributes.only_sym_link) {
		handleToggle = () => {
		};
	}

	return (
		<li>
			<BulletWithSymbol
				expanded={expandYear}
				expandSubLevel={yearObj.expand}
				attributes={attributes}
				title={yearObj.year}
				permalink={yearObj.permalink}
				onToggle={loadMonths}
			/>
			<a href={yearObj.permalink} title={yearObj.title} onClick={handleToggle}>{lnkYear}</a>
			{
				loading ? (<ul><li>{__('Loading...', 'jalw')}</li></ul>) : ''
			}
			{
				months.length > 0 ? (
					<ul className="jaw_months">
						months.map((yearObj) =>
						{/*<DisplayMonth*/}
						{/*	yearObj={yearObj}*/}
						{/*	attributes={attributes}*/}
						{/*	currentConfig={currentConfig}*/}
						{/*/>*/}
					</ul>
				) : ''
			}
		</li>
	);
}

export default DisplayYear;
