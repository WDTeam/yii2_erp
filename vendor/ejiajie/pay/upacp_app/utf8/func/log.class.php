<?php 
	class PhpLog
	{
		const DEBUG = 1;// Most Verbose
		const INFO = 2;// ...
		const WARN = 3;// ...
		const ERROR = 4;// ...
		const FATAL = 5;// Least Verbose
		const OFF = 6;// Nothing at all.
		 
		const LOG_OPEN = 1;
		const OPEN_FAILED = 2;
		const LOG_CLOSED = 3;
		 
		/* Public members: Not so much of an example of encapsulation, but that's okay. */
		public $Log_Status = PhpLog::LOG_CLOSED;
		public $DateFormat= "Y-m-d G:i:s";
		public $MessageQueue;
		 
		private $filename;
		private $log_file;
		private $priority = PhpLog::INFO;
		 
		private $file_handle;
		
		/**
		 * AUTHOR:	gu_yongkang
		 * DATA:	20110322
		 * Enter description here ...
		 * @param $filepath
		 * ???????
		 * @param $timezone
		 * ??????????"PRC"????
		 * @param $priority
		 * ??????
		 */

		public function __construct( $filepath, $timezone, $priority )
		{
			if ( $priority == PhpLog::OFF ) return;

			$this->filename = date('Y-m-d', time()) . '.log';	//???????.log?????
			$this->log_file = $this->createPath($filepath, $this->filename);
			$this->MessageQueue = array();
			$this->priority = $priority;
			date_default_timezone_set($timezone);

			if ( !file_exists($filepath) )	//??????????
			{
				if(!empty($filepath))	//????????
				{
					if(!($this->_createDir($filepath)))
					{
						die("??????!");
					}
					if ( !is_writable($this->log_file) )
					{
					$this->Log_Status = PhpLog::OPEN_FAILED;
					$this->MessageQueue[] = "The file exists, but could not be opened for writing. Check that appropriate permissions have been set.";
					return;
					}
				}
			}

			if ( $this->file_handle = fopen( $this->log_file , "a+" ) )
			{
				$this->Log_Status = PhpLog::LOG_OPEN;
				$this->MessageQueue[] = "The log file was opened successfully.";
			}
			else
			{
				$this->Log_Status = PhpLog::OPEN_FAILED;
				$this->MessageQueue[] = "The file could not be opened. Check permissions.";
			}
			return;
		}

		public function __destruct()
		{
			if ( $this->file_handle )
			fclose( $this->file_handle );
		}
		
		/**
	     *??:????
	     *??:??????
	     *??:true | false
	     */
		private  function _createDir($dir)
		{
			return is_dir($dir) or (self::_createDir(dirname($dir)) and mkdir($dir, 0777));
		}
		
		/**
	     *??:????
	     *??:?????,???????
	     *??:????????
	     */
		private function createPath($dir, $filename)
		{
			if (empty($dir)) 
			{
				return $filename;
			} 
			else 
			{
				return $dir . "/" . $filename;
			}
		}
		 
		public function LogInfo($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * ?????????????
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::INFO, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}
		 
		public function LogDebug($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * ?????????????
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::DEBUG, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}
		 
		public function LogWarn($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * ?????????????
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::WARN, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}
		 
		public function LogError($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * ?????????????
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::ERROR, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}
		 
		public function LogFatal($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * ?????????????
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::FATAL, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}

		/**
		 * Author ? gu_yongkang
		 * Enter description here ...
		 * @param unknown_type $line
		 * content ??
		 * @param unknown_type $priority
		 * ????
		 * @param unknown_type $sFile
		 * ??????????
		 * @param unknown_type $iLine
		 * ???????????
		 */
		public function Log($line, $priority, $sFile, $iLine)
		{
			if ($iLine > 0)
			{
				//$line = iconv('GBK', 'UTF-8', $line);
				if ( $this->priority <= $priority )
				{
					$status = $this->getTimeLine( $priority, $sFile, $iLine);
					$this->WriteFreeFormLine ( "$status $line \n" );
				}
			}
			else 
			{
				/**
				 * AUTHOR : gu_yongkang
				 * ?????????????
				 */
				$sAarray = array();
				$sAarray = debug_backtrace();
				$sGetFilePath = $sAarray[0]["file"];
				$sGetFileLine = $sAarray[0]["line"];
				if ( $this->priority <= $priority )
				{
					$status = $this->getTimeLine( $priority, $sGetFilePath, $sGetFileLine);
					unset($sAarray);
					unset($sGetFilePath);
					unset($sGetFileLine);
					$this->WriteFreeFormLine ( "$status $line \n" );
				}
			}
		}
		 // ????????
		public function WriteFreeFormLine( $line )
		{
			if ( $this->Log_Status == PhpLog::LOG_OPEN && $this->priority != PhpLog::OFF )
			{
				if (fwrite( $this->file_handle , $line ) === false) 
				{
					$this->MessageQueue[] = "The file could not be written to. Check that appropriate permissions have been set.";
				}
			}
		}
		private function getRemoteIP()
		{
			foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
			{
				if (array_key_exists($key, $_SERVER) === true)
				{
					foreach (explode(',', $_SERVER[$key]) as $ip)
					{
						$ip = trim($ip);
						if (!empty($ip))
						{
							return $ip;
						}
					}
				}
			}
			return "_NO_IP";
		}
		 
		private function getTimeLine( $level, $FilePath, $FileLine)
		{			
			$time = date( $this->DateFormat );
			$ip = $this->getRemoteIP();
			switch( $level )
			{
				case PhpLog::INFO:
				return "$time, " . "INFO, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				case PhpLog::WARN:
				return "$time, " . "WARN, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				case PhpLog::DEBUG:
				return "$time, " . "DEBUG, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				case PhpLog::ERROR:
				return "$time, " . "ERROR, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				case PhpLog::FATAL:
				return "$time, " . "FATAL, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				default:
				return "$time, " . "LOG, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
			}
		}
	}
?>
