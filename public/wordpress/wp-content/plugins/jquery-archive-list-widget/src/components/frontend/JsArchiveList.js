import {useEffect, useState} from '@wordpress/element';
import {__} from "@wordpress/i18n";
import apiFetch from '@wordpress/api-fetch';
import DisplayYear from "./components/DisplayYear";
import {useSelect} from "@wordpress/data";

const JsArchiveList = ({attributes}) => {
	const [loading, setLoading] = useState(true);
	const [archiveList, setArchive] = useState([]);
	const [currentConfig, setCurrentConfig] = useState({
		curPostYear: null,
		curPostMonth: null,
	});
	const postId = useSelect(select => select("core/editor").getCurrentPostId());

	const loadYears = () => {
		setLoading(true);

		const params = new URLSearchParams();
		params.append('type', attributes.type || 'post');
		params.append('onlycat', attributes.onlycategory || '');
		params.append('expand', attributes.expand || '');

		if (attributes.categories) {
			params.append('exclusionType', attributes.include_or_exclude);
			params.append('cats', attributes.categories);
		}

		if (postId) {
			params.append('postId', postId);
		}

		apiFetch({path: `/jalw/v1/years?${params.toString()}`}).then((data) => {
			setArchive(data.years);
			setCurrentConfig({
				curPostYear: data.current_post_year,
				curPostMonth: data.current_post_month,
			})
			setLoading(false);
		});
	};

	// useEffect(() => {
	// 	loadYears();
	// }, [postId]);

	return (
		<div className="js-archive-list">
			<h3>{attributes.title}</h3>
			{loading ? (
				<div>
					{__('Loading...', 'jalw')}
				</div>
			) : (
				<ul className="jaw_widget preload">
					{
						archiveList.length === 0 ? (
							<li>{__( 'There are no post to show.', 'jalw' )}</li>
						) :
							archiveList.map((yearObj)=>
								<li>
									<DisplayYear
										yearObj={yearObj}
										attributes={attributes}
										currentConfig={currentConfig}
									/>
								</li>
							)
					}
				</ul>
				)}
		</div>
	);
};

export default JsArchiveList;
