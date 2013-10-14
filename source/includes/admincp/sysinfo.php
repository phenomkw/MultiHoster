<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1253491684 - Sunday, September 20, 2009, 08:08:04 PM EDT -0400
	// ======================================== /
	
	// ======================================== \
	// Written by: Mihalism Technologies (www.mihalism.net)
	// Contributions by: Michael Manley (mmanley@nasutek.com)
	//
	// Unix/Linux Functions based off: Debian GNU/Linux 5.0
	// Mac OS X (Darwin) Functions based off: Mac OS X 10.5.8 (9L30)
	// Windows Operating System Functions based off: Windows XP Professional (Service Pack 3)
	// ======================================== /
	
	// Running Processes
	
	function _get_processes()
	{
		// Mac OS X uses different method to output "top" list instead
		// of continuously updating it, so let's define what command it is.
		
		$topcmd = ((IS_DARWIN_OS == true) ? "top -l 1" : "top -b -n 1");
		
		$command = ((IS_WINDOWS_OS == true) ? "tasklist" : $topcmd);
			
		$processlist = @shell_exec($command);
		
		return (($topinfo === false) ? false : trim($processlist));
	}

	// Disk Space Information

	function _get_diskspace_info()
	{
		// Check root file system (if possible) to get total system 
		// usage, but some hosts jail us in, so let us check options.
		
		$root_path = ((IS_WINDOWS_OS == true) ? "C:" : "/");
		
		// PHP being jailed is naughty and shows a lack of security on
		// the webmaster's end, but we still have to check for it. The
		// good thing is, PHP is finally taking steps to remove the ability
		// for webmasters to enable safe_mode. safe_mode is now deprecated.
		
		$check_path = ((PHP_IS_JAILED == true || is_readable($root_path) == false) ? "." : $root_path);
		
		// The more functions PHP makes native for outputting system information
		// such as the diskspace functions shown below, means less work-arounds for
		// differences found in each Operating System that might be used by server.
		
		$free_space = disk_free_space($check_path);
		$total_space = disk_total_space($check_path);
		
		if (is_float($free_space) == false || is_float($total_space) == false) {
			return false;	
		} else {
			$used_space = ($total_space - $free_space);
			
			return array(
				"used" => $used_space,
				"free" => $free_space,
				"total" => $total_space, 
			);
		}
	}
	
	// Memory Information
	
	function _get_memory_info()
	{
		if (IS_WINDOWS_OS == true) {
			// Get Memory Information for Windows Operating System
		
			$obj = new COM('winmgmts:{impersonationLevel=impersonate}//./root/cimv2');
				
			// Total Available Memory
			
			foreach ($obj->instancesof("Win32_ComputerSystem") as $mp) {
				$ram_total = $mp->TotalPhysicalMemory;
					
				break;
			}
				
			// Free Memory and Swap Memory
			
			foreach ($obj->instancesof("Win32_OperatingSystem") as $mp) {
				$ram_free = $mp->FreePhysicalMemory;
				$swap_free = $mp->FreeVirtualMemory;
				$swap_total = $mp->TotalVirtualMemorySize;
				
				$swap_used = ($swap_total - $swap_free);
				$ram_used = ($ram_total - ($ram_free * 1024));
				
				break;
			}
		} elseif (IS_DARWIN_OS == true) {
			// Get Memory Information for Mac OS X (Darwin)
			
			// Total Available Memory
			
			$ram_info = @shell_exec("sysctl hw.memsize");
			
			if ($ram_info === false) {
				return false;
			} else {
				$ram_info = explode(" ", $ram_info);
				
				$ram_total = (int)$ram_info['1'];
			}
			
			// Free Memory
			
			$ram_info = @shell_exec("vm_stat");
			
			if ($ram_info === false) {
				return false;
			} else {
				preg_match("#Pages free:\s+([^\n]+)\.\n#", $ram_info, $matches);
				
				if (isset($matches['1']) == false) {
					return false;	
				} else {
					$ram_free = ((int)$matches['1'] * 4096);
					
					$ram_used = ($ram_total - $ram_free);
				}
			}
			
			// Total and Free Swap Memory
			
			$swap_info = @shell_exec("sysctl vm.swapusage");
			
			if ($swap_info === false) {
				return false;
			} else {
				preg_match_all("#([a-zA-Z0-9]+) = ([^M]+)#i", $swap_info, $matches);
				
				$variable_names = array("total" => "swap_total", "free" => "swap_free");
				
				foreach ($matches['1'] as $id => $value) {
					if (array_key_exists($value, $variable_names) == true) {
						$variable_name = $variable_names[$value];
						$$variable_name = ($matches['2'][$id] * 1048576);
					}
				}
				
				$swap_used = ($swap_total - $swap_free);
			}
		} else {
			// Get Memory Information for Linux/Unix Operating Systems
			
			$ram_usage = @shell_exec("cat /proc/meminfo");
			
			if ($ram_usage === false) {
				return false;
			} else {
				$variable_names = array("SwapTotal:" => "swap_total", "SwapFree:" => "swap_free", "MemTotal:" => "ram_total", "MemFree:" => "ram_free", "Buffers:" => "ram_buffers", "Cached:" => "ram_cached");
					
				preg_match_all("#([^\s]+)\s+([0-9]+)\s*kB\n#i", $ram_usage, $ram_info);
			
				foreach ($ram_info['1'] as $id => $value) {
					if (array_key_exists($value, $variable_names) == true) {
						$variable_name = $variable_names[$value];
						$$variable_name = ($ram_info['2'][$id] * 1024);
					}
				}
		
				$ram_free = ($ram_free + $ram_cached + $ram_buffers);
				$ram_used = ($ram_total - $ram_free);
				
				$swap_used = ($swap_total - $swap_free);
			}
		}
		
		// Check Reported Memory Data
		
		if (isset($ram_total, $ram_free, $swap_free, $swap_total) === false) {
			return false;
		} else {
			// Output Memory Data
			
			return array(
				"ram" => array(
					"free" => $ram_free,
					"used" => $ram_used,
					"total" => $ram_total,
				),
				
				"swap" => array(
					"free" => $swap_free,
					"used" => $swap_used,
					"total" => $swap_total,
				),
			);
		}
	}	
	
	// Uptime Information
	
	function _get_uptime_info()
	{
		if (IS_WINDOWS_OS == true) {
			// Get Uptime Information for Windows Operating System
			
			$upsince = @filemtime("C:\pagefile.sys");
			
			if ($upsince === false) {
				return false;
			} else {
				$total_uptime = round(((time() - $upsince) / 86400), 1); 
			}
		} elseif (IS_DARWIN_OS == true) {
			// Get Uptime Information for Mac OS X (Darwin)
			
			$uptime_info = @shell_exec("sysctl -n kern.boottime");
			
			if ($uptime_info === false) {
				return false;
			} else {
				preg_match("#sec = ([0-9]{10})#", $uptime_info, $matches);
				
				if (isset($matches['1']) === false) {
					return false;	
				} else {
					$uptime_data = (time() - (int)$matches['1']);
					
					$total_uptime = round(($uptime_data / 86400), 1); 
				}
			}
		} else {
			// Get Uptime Information for Linux/Unix Operating Systems
			
			$uptime_info = @shell_exec("cat /proc/uptime");
			
			if ($uptime_info === false) {
				return false;
			} else {
				$uptime_data = explode(" ", $uptime_info, 2);
				 
				$total_uptime = round(($uptime_data['0'] / 86400), 1); 
			}
		}
		
		// Output Uptime Data
		
		return (int)$total_uptime;
	}
	
	// Processor (CPU) Information
	
	function _get_cpu_info()
	{
		if (IS_WINDOWS_OS == true) {
			// Get Processor Information for Windows Operating System
			
			$obj = new COM('winmgmts:{impersonationLevel=impersonate}//./root/cimv2');
				
			foreach ($obj->instancesof("Win32_Processor") as $mp) {
				$cpu_model = $mp->Name;
				$cpu_speed = $mp->CurrentClockSpeed;
				$cpu_usage = array($mp->LoadPercentage);
					
				break;
			}
		} elseif (IS_DARWIN_OS == true) {
			// Get Processor Information for Mac OS X (Darwin)
			
			// sys_getloadavg() is one of those functions made native by PHP
			// By making it native they made it easier to get load average for
			// the Operating System without having to do specific commands.
			
			$cpu_usage = sys_getloadavg();
			
			foreach ($cpu_usage as $id => $value) {
				$cpu_usage[$id] = number_format($value, 2);
			}
			
			// Get Processor Model and Speed
			
			$cpu_info = @shell_exec("system_profiler SPHardwareDataType");
			
			if ($cpu_info === false) {
				return false; 
			} else {
				$variable_names = array("Processor Name" => "cpu_model", "Processor Speed" => "cpu_speed");
			
				preg_match_all("#\s+([^\:]+):\s([^\n]+)\n#", $cpu_info, $matches);
				
				foreach ($matches['1'] as $id => $value) {
					if (array_key_exists($value, $variable_names) == true) {
						$variable_name = $variable_names[$value];
						$$variable_name = $matches['2'][$id];
					}
				}
				
				$cpu_speed = str_replace(" GHz", NULL, $cpu_speed);
			}
		} else {
			// Get Processor Information for Linux/Unix Operating Systems
			
			// Get Load Average
			
			$cpu_usage = sys_getloadavg();
			
			foreach ($cpu_usage as $id => $value) {
				$cpu_usage[$id] = number_format($value, 2);
			}
			
			// Get Processor Model and Speed
			
			$cpu_info = shell_exec("cat /proc/cpuinfo -T");
			
			if ($cpu_info === false) {
				return false; 
			} else {
				$variable_names = array("model name" => "cpu_model", "cpu MHz" => "cpu_speed");
					
				$cpu_info = str_replace("^I", NULL, $cpu_info);	
				
				preg_match_all("#([^:]+):\s([^\n]+)\n#i", $cpu_info, $cpu_minfo);
				
				foreach ($cpu_minfo['1'] as $id => $value) {
					if (array_key_exists($value, $variable_names) == true) {
						$variable_name = $variable_names[$value];
						$$variable_name = $cpu_minfo['2'][$id];
					}
				}
				
				$cpu_speed = @number_format(round(($cpu_speed / 1000), 2), 2);
			}
		}		
		
		// Check Processor Data
		
		if (isset($cpu_speed, $cpu_model, $cpu_usage) === false) {
			return false;	
		} else {
			// Output Processor Data
			
			return array(
				"load" => $cpu_usage,
				"model" => $cpu_model,
				"speed" => $cpu_speed,
			);
		}
	}
	
	// Operating System Information
	
	function _get_system_name()
	{
		if (IS_WINDOWS_OS == true) {
			// Get System Version for Windows Operating System
			
			$obj = new COM('winmgmts:{impersonationLevel=impersonate}//./root/cimv2');
				
			foreach ($obj->instancesof("Win32_OperatingSystem") as $mp) {
				$system_version = $mp->Caption;
					
				break;
			}
		} elseif (IS_DARWIN_OS == true) {
			// Get System Version for Mac OS X (Darwin)
			
			$version_info = @shell_exec("system_profiler SPSoftwareDataType");
			
			if ($version_info === false) {
				return false;
			} else {
				preg_match("#System Version: ([^\n]+)#i", $version_info, $matches);
				
				if (isset($matches['1']) === false) {
					return false;
				} else {
					$system_version = $matches['1'];
				}
			}
		} else {
			// Get System Version for Linux/Unix Operating Systems
			
			$version_info = @shell_exec("cat /etc/issue");
			
			if ($version_info === false) {
				return false;
			} else {
				$system_version = str_replace(array("\\n", "\\l"), NULl, trim($version_info));
			}
		}	
		
		// Output System Version
		
		return ((isset($system_version) === false) ? false : (string)$system_version);
	}

?>