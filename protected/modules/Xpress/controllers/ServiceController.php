<?php
/**
 * $Id$
 *
 * @author Hung Nguyen <hung5s@gmail.com>
 */

/**
 * Class ServiceController
 *
 * Act as a service gateway of the application, allow direct access to the service
 * via HTTP request using SID to specify service name.
 *
 * A very useful feature of this service gateway is for AJAX calling services and
 * get JSON result for use in the page. It is recommended that you use AJAX POST
 * for all request.
 *
 * This class is still under development to integrate with Restler. At the moment,
 * having actionIndex enable is NOT SAFE as it will expose all the services. In the
 * meantime, non-ajax request to service can be done via /command. This implementation
 * relies on the developer to write service in a secure manner. It limit the access
 * to only services start with 'cmd' as a reminder to developers that "this command
 * is exposed to the world".
 */
class ServiceController extends XController
{
	/**
	 * Run only services whose names begins with 'cmd'
	 *
	 * @param mixed $SID string Service ID
	 */
	public function actionCommand()
	{
		$SID = $this->get('SID', '');
		//check the service ID
		$tmp = explode('.', $SID);
		if (count($tmp) != 3) {
			throw new CHttpException(400, 'Service not found.');
		}
		if (substr($tmp[2], 0, 3) != 'cmd') {
			throw new CHttpException(400, 'Cannot execute this command.');
		}

		$result = $this->api($SID, $_GET);
		if (isset($_GET['returnUrl'])) {
			$this->redirect($_GET['returnUrl']);
		}
	}

	/**
	 * Serve a RESTful web service request
	 *
	 * IT IS NOT SAFE TO USE THE ACTION IN PRODUCTION.
	 */
	public function actionIndex()
	{
		throw new CException('Restful service is not securely implemented yet. Please use ajax request if possible.');
		//TODO: Use http://code.google.com/p/oauth-php/ for OAuth
//        if (count($_POST))
//            $data = $_POST;
//        else
//            $data = $_GET;
//
//        if (!isset($data['SID'])) return;
//        $sid = $data['SID'];
//
//        $result = $this->api($sid, $data);
//
//        if (isset($data['ajax']) && isset($data['validateOnly']) && $data['validateOnly'] == true)
//            // special support for ajax validation
//            echo $result->getActiveErrorMessages($result->model);
//        elseif (isset($data['FORMAT']) && $data['FORMAT'] == 'text')
//            echo CVarDumper::dumpAsString($result);
//        else
//            echo '('.CJSON::encode($result).')';
	}

	/**
	 * Serve an Ajax request of a service
	 */
	public function actionAjax()
	{
		if (!Yii::app()->request->IsAjaxRequest) {
			throw new CHttpException(400, 'Not an Ajax request.');
		}

		if (count($_POST)) {
			$data = $_POST;
		} else {
			$data = $_GET;
		}

		if (!isset($data['SID'])) {
			return;
		}
		$sid = $data['SID'];

		$result = $this->api($sid, $data);

		if (isset($data['ajax']) && isset($data['validateOnly']) && $data['validateOnly'] == true) // special support for ajax validation
		{
			echo $result->getActiveErrorMessages($result->model);
		} elseif (isset($data['FORMAT']) && $data['FORMAT'] == 'text') {
			echo CVarDumper::dumpAsString($result);
		} else {
			echo '(' . CJSON::encode($result) . ')';
		}
	}

	/**
	 * Serve an widget for an Ajax request
	 */
	public function actionWidget()
	{
		if (!Yii::app()->request->IsAjaxRequest) {
			throw new CHttpException(400, 'Not an Ajax request.');
		}

		if (count($_POST)) {
			$data = $_POST;
		} else {
			$data = $_GET;
		}

		$wid = $data['WID'];
		unset($data['WID']);
		echo $this->widget($wid, $data, true);
	}
}

?>